<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TotalRevenueController extends Controller
{
    public function index()
    {
        // Assuming your table name is `bplo_records`
        $overallTotal = DB::table('bplo_records')->sum('AMOUNT');

        return response()->json([
            'overall_total' => $overallTotal
        ]);
    }
}
