<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_number',
        'customer_id',
        'created_by',
        'origin_address',
        'origin_city',
        'origin_lat',
        'origin_lng',
        'destination_address',
        'destination_city',
        'destination_lat',
        'destination_lng',
        'receiver_name',
        'receiver_phone',
        'weight_kg',
        'description',
        'cost',
        'status',
        'expected_delivery',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'origin_lat'        => 'decimal:7',
            'origin_lng'        => 'decimal:7',
            'destination_lat'   => 'decimal:7',
            'destination_lng'   => 'decimal:7',
            'weight_kg'         => 'decimal:2',
            'cost'              => 'decimal:2',
            'expected_delivery' => 'date',
            'delivered_at'      => 'datetime',
        ];
    }

    // -------------------------
    // Relationships
    // -------------------------
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function events()
    {
        return $this->hasMany(ShipmentEvent::class)->latest();
    }

    public function assignments()
    {
        return $this->hasMany(ShipmentAssignment::class);
    }

    public function activeAssignment()
    {
        return $this->hasOne(ShipmentAssignment::class)
                    ->whereNull('unassigned_at')
                    ->latestOfMany();
    }

    // -------------------------
    // Status helpers
    // -------------------------
    public static function statuses(): array
    {
        return [
            'pending'          => 'Pending',
            'picked_up'        => 'Picked Up',
            'in_transit'       => 'In Transit',
            'out_for_delivery' => 'Out for Delivery',
            'delivered'        => 'Delivered',
            'failed'           => 'Failed',
            'cancelled'        => 'Cancelled',
        ];
    }

    public function statusLabel(): string
    {
        return self::statuses()[$this->status] ?? ucfirst($this->status);
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function isCancellable(): bool
    {
        return in_array($this->status, ['pending', 'picked_up']);
    }

    public function daysUntilDelivery(): ?int
{
    if (! $this->expected_delivery) {
        return null;
    }

    return now()->startOfDay()->diffInDays($this->expected_delivery->startOfDay(), false);
}

public function etaLabel(): string
{
    if ($this->isDelivered()) {
        return 'Delivered';
    }

    $days = $this->daysUntilDelivery();

    if ($days === null) {
        return 'No ETA set';
    }

    if ($days < 0) {
        return abs($days) . ' day' . (abs($days) === 1 ? '' : 's') . ' overdue';
    }

    if ($days === 0) {
        return 'Due today';
    }

    if ($days === 1) {
        return 'Due tomorrow';
    }

    return "In {$days} days";
}

public function isOverdue(): bool
{
    return ! $this->isDelivered()
        && $this->expected_delivery
        && $this->expected_delivery->isPast();
}
}