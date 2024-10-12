<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    use HasFactory;
    protected $fillable = [
        'month',
        'year',
    ];

    public function getNameAttribute()
    {
        return $this->month . '/' . $this->year;
    }


    // Define the relationship with debts and credits
    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

    public function credits()
    {
        return $this->hasMany(Credit::class);
    }
}
