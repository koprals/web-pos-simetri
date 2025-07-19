<?php

namespace App\Http\Controllers;

use App\Exports\RentalEquipmentsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RentalEquipmentExportController extends Controller
{
    public function export(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');

        return Excel::download(new RentalEquipmentsExport($from, $to), 'rental-equipments-'.now()->format('Ymd_His').'.xlsx');
    }
}
