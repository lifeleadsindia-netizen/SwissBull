<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelIncome extends Model
{
    protected $table = 'level_incomes';

    protected $guarded = [];

    protected $casts = [
        'level' => 'integer',
        'rate' => 'float',
        'package' => 'float',
        'amount' => 'float',
    ];
}
