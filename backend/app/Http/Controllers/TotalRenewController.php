<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TotalRenewController extends Controller
{
    public function index()
    {
        $today = Carbon::now();
        $nextMonth = $today->copy()->addDays(30);

        // ✅ Count renewals that are still valid beyond next 30 days
        $totalRenew = DB::table('bplo_records')
            ->where('RENEW_TO', '>', $nextMonth)
            ->count();

        return response()->json([
            'overall_renew' => (int) $totalRenew,
            'as_of' => $today->format('Y-m-d'),
        ]);
    }
}
