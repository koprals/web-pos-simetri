<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrderExportController extends Controller
{
    public function export(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');

        return Excel::download(new OrdersExport($from, $to), 'orders-'.now()->format('Ymd_His').'.xlsx');
    }
}
