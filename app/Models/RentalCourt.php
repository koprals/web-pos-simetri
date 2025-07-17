<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalCourt extends Model
{
    protected $fillable = [
        'court_id',
        'name',
        'phone',
        'total_price',
        'note',
        'start_time',
        'end_time',
        'payment_method_id',
        'paid_amount',
        'change_amount',
    ];

    /**
     * Relasi ke Model Court (Lapangan)
     */
    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    /**
     * Relasi ke Model PaymentMethod
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * Accessor: Format Total Price ke Rupiah
     */
    public function getTotalPriceFormattedAttribute()
    {
        return 'Rp'.number_format($this->total_price, 0, ',', '.');
    }

    /**
     * Accessor: Format Paid Amount ke Rupiah
     */
    public function getPaidAmountFormattedAttribute()
    {
        return 'Rp'.number_format($this->paid_amount, 0, ',', '.');
    }

    /**
     * Accessor: Format Change Amount ke Rupiah
     */
    public function getChangeAmountFormattedAttribute()
    {
        return 'Rp'.number_format($this->change_amount, 0, ',', '.');
    }

    /**
     * Scope: Ambil Rental berdasarkan tanggal
     */
    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('start_time', $date);
    }
}
