<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = [];

    /**
     * Scope a query to only include notifications relevant for a given member.
     */
    public function scopeForMember($query, ?string $memberid)
    {
        $cleanId = $memberid ? strtoupper(trim((string) $memberid)) : '';

        return $query->where(function ($q) use ($cleanId) {
            // 1. Broadcast / All Users notifications
            $q->where(function ($sub) {
                $sub->whereNull('memberid')
                    ->orWhere('memberid', '')
                    ->orWhere('memberid', '0')
                    ->orWhereRaw("TRIM(COALESCE(memberid, '')) IN ('', '0', 'null', 'NULL', 'all', 'ALL', 'none', 'NONE')")
                    ->orWhereRaw("LOWER(TRIM(COALESCE(type, ''))) IN ('all users', 'all user', 'all', 'all_users', 'allusers', 'broadcast', 'public', 'general')")
                    ->orWhereRaw("LOWER(TRIM(COALESCE(type, ''))) LIKE '%all%'")
                    ->orWhereRaw("LOWER(TRIM(COALESCE(type, ''))) LIKE '%broadcast%'")
                    ->orWhereRaw("LOWER(TRIM(COALESCE(type, ''))) LIKE '%general%'");
            });

            // 2. Specific Member targeted notifications
            if ($cleanId !== '') {
                $q->orWhere(function ($sub) use ($cleanId) {
                    $sub->where('memberid', $cleanId)
                        ->orWhereRaw("UPPER(TRIM(COALESCE(memberid, ''))) = ?", [$cleanId]);
                });
            }
        });
    }
}
