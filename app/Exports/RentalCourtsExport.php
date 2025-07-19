<?php

namespace App\Exports;

use App\Models\RentalCourt;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RentalCourtsExport implements FromCollection, WithHeadings
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
        $query = RentalCourt::query();

        if ($this->from) {
            $query->whereDate('created_at', '>=', $this->from);
        }
        if ($this->to) {
            $query->whereDate('created_at', '<=', $this->to);
        }

        return $query->get([
            'id', 'court_id', 'name', 'phone', 'total_price', 'paid_amount', 'change_amount', 'start_time', 'end_time', 'created_at',
        ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Lapangan',
            'Nama Penyewa',
            'Telepon',
            'Total Harga',
            'Dibayar',
            'Kembalian',
            'Mulai',
            'Selesai',
            'Tanggal Dibuat',
        ];
    }
}
