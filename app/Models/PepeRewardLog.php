<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PepeRewardLog extends Model
{
    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(MemberDetail::class, 'member_id', 'memberid');
    }
}
