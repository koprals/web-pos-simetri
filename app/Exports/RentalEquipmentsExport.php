<?php

namespace App\Exports;

use App\Models\RentalEquipment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RentalEquipmentsExport implements FromCollection, WithHeadings
{
    protected $from;

    protected $to;

    public function __construct($from = null, $to = null)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        $query = RentalEquipment::query();

        if ($this->from) {
            $query->whereDate('created_at', '>=', $this->from);
        }
        if ($this->to) {
            $query->whereDate('created_at', '<=', $this->to);
        }

        return $query->get([
            'id', 'name', 'phone', 'total_price', 'paid_amount', 'change_amount', 'created_at',
        ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Penyewa',
            'Telepon',
            'Total Harga',
            'Dibayar',
            'Kembalian',
            'Tanggal Dibuat',
        ];
    }
}
