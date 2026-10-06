<?php

namespace App\Providers;

use App\Models\MemberDetail;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('member.*', function ($view) {
            $recentNotifications = collect();
            $unreadCount = 0;
            $readIds = [];

            if (session()->has('MEMBER_ID')) {
                $memberid = strtoupper(trim((string) session('MEMBER_ID')));

                // 1. Fetch read IDs safely
                try {
                    $member = MemberDetail::where('memberid', $memberid)
                        ->orWhereRaw('UPPER(TRIM(memberid)) = ?', [$memberid])
                        ->first();

                    if ($member && ! empty($member->file_read)) {
                        if (is_array($member->file_read)) {
                            $readIds = $member->file_read;
                        } elseif (is_string($member->file_read)) {
                            $decoded = json_decode($member->file_read, true);
                            if (is_array($decoded)) {
                                $readIds = $decoded;
                            } else {
                                $readIds = array_filter(explode(',', $member->file_read), 'is_numeric');
                            }
                        }
                    }
                    $readIds = array_values(array_unique(array_map('intval', (array) $readIds)));

                    // Stale database dump detection & sanitization:
                    // If readIds contain ghost IDs (IDs that do not exist in notifications table),
                    // prune them or reset if corrupt dump detected (3+ ghost IDs or more ghost IDs than existing notifications).
                    if (! empty($readIds)) {
                        $allExistingNotifIds = Notification::pluck('id')->map(fn ($id) => (int) $id)->toArray();
                        $ghostIds = array_diff($readIds, $allExistingNotifIds);

                        if (count($ghostIds) >= 3 || (count($readIds) > count($allExistingNotifIds) && count($ghostIds) > 0)) {
                            // Stale dump IDs detected! Reset readIds so genuine notifications show as unread / NEW
                            $readIds = [];
                            if ($member) {
                                try {
                                    $member->file_read = [];
                                    $member->save();
                                } catch (\Throwable $ex) {
                                    // Ignore save failure
                                }
                            }
                        } else {
                            $readIds = array_values(array_intersect($readIds, $allExistingNotifIds));
                        }
                    }
                } catch (\Throwable $e) {
                    Log::warning('AppServiceProvider readIds load warning: '.$e->getMessage());
                    $readIds = [];
                }

                // 2. Fetch notifications for this member
                try {
                    $allMemberNotifs = Notification::forMember($memberid)
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();

                    $unreadCount = $allMemberNotifs->reject(function ($n) use ($readIds) {
                        return in_array((int) $n->id, $readIds, true);
                    })->count();

                    $recentNotifications = $allMemberNotifs;
                } catch (\Throwable $e) {
                    Log::error('AppServiceProvider forMember query error: '.$e->getMessage());
                    try {
                        $fallbackNotifs = Notification::whereNull('memberid')
                            ->orWhere('memberid', '')
                            ->orWhere('memberid', '0')
                            ->orWhere('memberid', $memberid)
                            ->orWhereRaw('LOWER(type) LIKE ?', ['%all%'])
                            ->orderBy('created_at', 'desc')
                            ->limit(10)
                            ->get();

                        $unreadCount = $fallbackNotifs->reject(function ($n) use ($readIds) {
                            return in_array((int) $n->id, $readIds, true);
                        })->count();

                        $recentNotifications = $fallbackNotifs;
                    } catch (\Throwable $e2) {
                        Log::error('AppServiceProvider fallback query error: '.$e2->getMessage());
                    }
                }
            }

            $view->with('headerNotifications', $recentNotifications);
            $view->with('unreadNotifCount', $unreadCount);
            $view->with('readIds', $readIds);

            // Only provide 'notifications' if the view does not already have its own notifications from a controller
            if (! array_key_exists('notifications', $view->getData())) {
                $view->with('notifications', $recentNotifications);
            }
        });
    }
}
