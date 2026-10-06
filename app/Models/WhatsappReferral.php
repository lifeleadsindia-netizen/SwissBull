<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappReferral extends Model
{
    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(MemberDetail::class, 'member_id', 'memberid');
    }

    public function message()
    {
        return $this->belongsTo(WhatsappReferralMessage::class, 'message_id');
    }
}
