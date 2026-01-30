<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'fee',
        'is_active',
    ];

    /**
     * Scope to get active zones only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order zones with priority (Cairo, Giza, Qalyubia first)
     */
    public function scopeOrdered($query)
    {
        return $query->orderByRaw("
            CASE 
                WHEN name LIKE '%القاهرة%' THEN 1 
                WHEN name LIKE '%الجيزة%' THEN 2 
                WHEN name LIKE '%القليوبية%' THEN 3 
                ELSE 4 
            END ASC
        ")->orderBy('name', 'ASC');
    }
}
