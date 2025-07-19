<?php

namespace App\Http\Controllers;

use App\Exports\RentalCourtsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RentalCourtExportController extends Controller
{
    public function export(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');

        return Excel::download(new RentalCourtsExport($from, $to), 'rental-courts-'.now()->format('Ymd_His').'.xlsx');
    }
}
