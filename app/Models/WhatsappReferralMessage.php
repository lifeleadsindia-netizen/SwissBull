<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappReferralMessage extends Model
{
    protected $guarded = [];

    protected $casts = [
        'target_member_ids' => 'array',
    ];
}
