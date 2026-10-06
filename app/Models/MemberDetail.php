<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberDetail extends Model
{
    protected $guarded = [];

    protected $casts = [
        'file_read' => 'array',
        'pepe_wallet' => 'float',
    ];

    public function whatsappReferrals()
    {
        return $this->hasMany(WhatsappReferral::class, 'member_id', 'memberid');
    }
}
