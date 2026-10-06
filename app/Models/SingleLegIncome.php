<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SingleLegIncome extends Model
{
    use HasFactory;

    protected $table = 'single_leg_incomes';

    protected $guarded = [];
}
