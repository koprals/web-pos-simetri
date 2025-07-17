<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentRental extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_equipment_id',
        'equipment_id',
        'quantity',
        'unit_price',
    ];

    public function rentalEquipment(): BelongsTo
    {
        return $this->belongsTo(RentalEquipment::class);
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }
}
