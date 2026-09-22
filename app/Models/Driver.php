<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'license_number',
        'status',
    ];

    // -------------------------
    // Relationships
    // -------------------------
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignments()
    {
        return $this->hasMany(ShipmentAssignment::class);
    }

    public function activeAssignments()
    {
        return $this->hasMany(ShipmentAssignment::class)
                    ->whereNull('unassigned_at');
    }

    // -------------------------
    // Helpers
    // -------------------------
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }
}