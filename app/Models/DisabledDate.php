<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisabledDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'disabled_date',
        'description'
    ];

    // Relation to Booking model if needed
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
