<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TotalExpiryController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $nextMonth = Carbon::today()->addMonth();

        // Count records that will expire within the next 30 days
        $expiringCount = DB::table('bplo_records')
            ->whereBetween('RENEW_TO', [$today, $nextMonth])
            ->count();

        return response()->json([
            'overall_expiry' => $expiringCount
        ]);
    }
}
