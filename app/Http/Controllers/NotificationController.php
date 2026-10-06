<?php

namespace App\Http\Controllers;

use App\Models\MemberDetail;
use App\Models\Notification;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class NotificationController extends Controller
{
    /**
     * Ensure required columns exist on notifications and member_details across environments.
     */
    public static function ensureNotificationColumnsExist(): void
    {
        static $checked = false;
        if ($checked) {
            return;
        }
        $checked = true;

        try {
            if (Schema::hasTable('member_details') && ! Schema::hasColumn('member_details', 'file_read')) {
                Schema::table('member_details', function (Blueprint $table) {
                    $table->longText('file_read')->nullable();
                });
            }

            if (Schema::hasTable('notifications')) {
                try {
                    DB::statement('ALTER TABLE notifications MODIFY COLUMN memberid VARCHAR(100) NULL DEFAULT NULL');
                } catch (\Throwable $e) {
                    // Ignore if already nullable or not permitted
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Auto-migration ensureNotificationColumnsExist: '.$e->getMessage());
        }
    }

    /**
     * Helper to get member and read IDs array safely.
     *
     * @return array{0: ?MemberDetail, 1: array<int>}
     */
    private function getMemberAndReadIds(?string $memberid): array
    {
        self::ensureNotificationColumnsExist();

        if (empty($memberid)) {
            return [null, []];
        }

        $cleanId = strtoupper(trim((string) $memberid));
        $member = MemberDetail::whereRaw('UPPER(TRIM(memberid)) = ?', [$cleanId])
            ->orWhere('memberid', $cleanId)
            ->first();

        $readIds = [];
        if ($member && ! empty($member->file_read)) {
            if (is_array($member->file_read)) {
                $readIds = $member->file_read;
            } elseif (is_string($member->file_read)) {
                $decoded = json_decode($member->file_read, true);
                if (is_array($decoded)) {
                    $readIds = $decoded;
                } else {
                    // Fallback to comma-separated string if stored that way
                    $readIds = array_filter(explode(',', $member->file_read), 'is_numeric');
                }
            }
        }
        $readIds = array_values(array_unique(array_map('intval', (array) $readIds)));

        if (! empty($readIds)) {
            $validNotifIds = Notification::pluck('id')->map(fn ($id) => (int) $id)->toArray();
            $ghostIds = array_diff($readIds, $validNotifIds);

            if (count($ghostIds) >= 3 || (count($readIds) > count($validNotifIds) && count($ghostIds) > 0)) {
                $readIds = [];
                if ($member) {
                    $this->persistReadIds($member, []);
                }
            } else {
                $readIds = array_values(array_intersect($readIds, $validNotifIds));
            }
        }

        return [$member, $readIds];
    }

    /**
     * Persist read IDs safely to member_details table.
     *
     * @param  array<int>  $readIds
     */
    private function persistReadIds(MemberDetail $member, array $readIds): void
    {
        $uniqueIds = array_values(array_unique(array_map('intval', $readIds)));
        $member->file_read = $uniqueIds;

        try {
            $member->save();
        } catch (\Throwable $e) {
            Log::error('Error saving member file_read via Eloquent: '.$e->getMessage());
            try {
                DB::table('member_details')
                    ->where('id', $member->id)
                    ->update(['file_read' => json_encode($uniqueIds)]);
            } catch (\Throwable $e2) {
                Log::error('Error saving member file_read via DB fallback: '.$e2->getMessage());
            }
        }
    }

    /**
     * Display all notifications for the logged-in member.
     */
    public function allNotifications(Request $request)
    {
        self::ensureNotificationColumnsExist();

        $memberid = strtoupper(trim((string) session('MEMBER_ID')));
        [$member, $readIds] = $this->getMemberAndReadIds($memberid);

        $notifications = Notification::forMember($memberid)
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadCount = $notifications->reject(function ($n) use ($readIds) {
            return in_array((int) $n->id, $readIds, true);
        })->count();

        return view('member.notification.all-notifications', [
            'data' => $member,
            'notifications' => $notifications,
            'readIds' => $readIds,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Display single notification detail and mark it as read.
     */
    public function showNotification(Request $request, $id)
    {
        self::ensureNotificationColumnsExist();

        $memberid = strtoupper(trim((string) session('MEMBER_ID')));
        [$member, $read] = $this->getMemberAndReadIds($memberid);

        $notification = Notification::forMember($memberid)
            ->where('id', $id)
            ->firstOrFail();

        if ($member) {
            $notifId = (int) $id;
            if (! in_array($notifId, $read, true)) {
                $read[] = $notifId;
                $this->persistReadIds($member, $read);
            }
        }

        return view('member.notification.notification-detail', [
            'data' => $member,
            'notification' => $notification,
        ]);
    }

    /**
     * Mark a single notification as read via AJAX.
     */
    public function markRead(Request $request)
    {
        self::ensureNotificationColumnsExist();

        $request->validate([
            'id' => 'required|integer',
        ]);

        $memberid = strtoupper(trim((string) session('MEMBER_ID')));
        [$member, $read] = $this->getMemberAndReadIds($memberid);

        if ($member) {
            $notifId = (int) $request->input('id');

            if (! in_array($notifId, $read, true)) {
                $read[] = $notifId;
                $this->persistReadIds($member, $read);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
        ]);
    }

    /**
     * Mark all unread notifications for logged-in member as read via AJAX.
     */
    public function markAllRead(Request $request)
    {
        self::ensureNotificationColumnsExist();

        $memberid = strtoupper(trim((string) session('MEMBER_ID')));
        [$member, $read] = $this->getMemberAndReadIds($memberid);

        if ($member) {
            $allNotifIds = Notification::forMember($memberid)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->toArray();

            $merged = array_unique(array_merge($read, $allNotifIds));
            $this->persistReadIds($member, $merged);
        }

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
            'unreadCount' => 0,
        ]);
    }
}
