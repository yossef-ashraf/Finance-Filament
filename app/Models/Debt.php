<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'amount',
        'note',
        'type',
        'month_id',
    ];

    // Define the relationship with the month
    public function month()
    {
        return $this->belongsTo(Month::class);
    }
}
