<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\WaterLevel;
use Carbon\Carbon;

class DashboardController extends Controller
{

public function masyarakat()
{
    $userId = auth()->id();

    $total_laporan = Report::where('user_id', $userId)->count();

    $userReports = Report::where('user_id', $userId)
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get();

    $allReports = Report::whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get();

    $groupedAllCoords = $allReports->groupBy(function($item) {
        return round($item->latitude,4) . ',' . round($item->longitude,4);
    });

    $wilayah_rawan = $groupedAllCoords->filter(fn($group) => $group->count() >= 2)
        ->map(fn($group) => [
            'address' => $group[0]->address ?? 'Wilayah Rawan',
            'lat' => $group[0]->latitude,
            'lng' => $group[0]->longitude,
            'count' => $group->count()
        ])->values();

    $wilayah_rawan_count = $wilayah_rawan->count();

    $total_koordinat = $groupedAllCoords->count();
    $potensi_banjir_percent = $total_koordinat
        ? round(($wilayah_rawan_count / $total_koordinat) * 100)
        : 0;

    $reportMarkers = $allReports->map(fn($r) => [
        'title' => $r->title,
        'lat' => $r->latitude,
        'lng' => $r->longitude,
        'status' => $r->status,
        'address' => $r->address
    ]);

    return view('dashboard.masyarakat', compact(
        'total_laporan',
        'wilayah_rawan_count',
        'potensi_banjir_percent',
        'reportMarkers',
        'wilayah_rawan'
    ));
}

  public function pemerintah()
{
    $reports = Report::with('user')
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get();

    $total_laporan   = $reports->count();
    $laporan_pending = $reports->where('status','pending')->count();
    $laporan_selesai = $reports->where('status','selesai')->count();
    $laporan_batal   = $reports->where('status','batal')->count();

    $reportMarkers = $reports->map(fn($r) => [
        'title' => $r->title,
        'lat'   => $r->latitude,
        'lng'   => $r->longitude,
        'address'=> $r->address ?? '-',
        'status'=> $r->status,

    ]);

    $groupedCoords = $reports->groupBy(fn($r) => $r->latitude.','.$r->longitude);

    $wilayah_rawan = [];
    foreach($groupedCoords as $group){
        if($group->count() >= 3){
            $wilayah_rawan[] = [
                'lat' => $group[0]->latitude,
                'lng' => $group[0]->longitude,
                'count' => $group->count()
            ];
        }
    }

    $newReports = Report::with('user')
        ->where('status', 'pending')
        ->whereDate('created_at', Carbon::today())
        ->latest()
        ->take(10)
        ->get();

    $notifCount = Report::where('status','pending')
        ->whereDate('created_at', Carbon::today())
        ->count();

    return view('dashboard.pemerintah', compact(
        'total_laporan',
        'laporan_pending',
        'laporan_batal',
        'laporan_selesai',
        'reportMarkers',
        'wilayah_rawan',
        'newReports',
        'notifCount',
    ));
}

}
