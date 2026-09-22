<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number',
        'type',
        'capacity_kg',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'capacity_kg' => 'decimal:2',
        ];
    }

    public function assignments()
    {
        return $this->hasMany(ShipmentAssignment::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }
}