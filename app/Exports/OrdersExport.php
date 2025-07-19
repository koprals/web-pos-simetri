<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
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
        $query = Order::query();

        if ($this->from) {
            $query->whereDate('created_at', '>=', $this->from);
        }
        if ($this->to) {
            $query->whereDate('created_at', '<=', $this->to);
        }

        return $query->get([
            'id', 'name', 'gender', 'total_price', 'paid_amount', 'change_amount', 'created_at',
        ]);
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Nama',
            'Gender',
            'Total Harga',
            'Bayar',
            'Kembalian',
            'Tanggal Pesanan',
        ];
    }
}
