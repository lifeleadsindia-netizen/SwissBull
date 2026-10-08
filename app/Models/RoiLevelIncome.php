<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoiLevelIncome extends Model
{
    use HasFactory;

    protected $table = 'roi_level_incomes';

    protected $guarded = [];

    protected $casts = [
        'level' => 'integer',
        'rate' => 'float',
        'staking_income' => 'float',
        'amount' => 'float',
    ];
}
