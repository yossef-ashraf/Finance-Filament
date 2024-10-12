<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentAndSavings extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'val',
        'amount',
        'note',
    ];
}
