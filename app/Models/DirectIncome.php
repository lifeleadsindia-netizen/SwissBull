<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DirectIncome extends Model
{
    protected $table = 'direct_incomes';

    protected $guarded = [];

    protected $casts = [
        'level' => 'integer',
        'rate' => 'float',
        'package' => 'float',
        'amount' => 'float',
    ];
}
