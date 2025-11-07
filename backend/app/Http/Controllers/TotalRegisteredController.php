<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TotalRegisteredController extends Controller
{
    public function index()
    {
        // Count total registered based on MCH_NO
        $overallTotal = DB::table('bplo_records')->count('MCH_NO');

        return response()->json([
            'overall_registered' => $overallTotal
        ]);
    }
}
