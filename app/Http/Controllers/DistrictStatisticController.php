<?php

namespace App\Http\Controllers;

use App\Models\DistrictStatistic;
use Illuminate\Http\Request;

class DistrictStatisticController extends Controller
{
    /**
     * Tampilkan halaman visualisasi peta tematik dan data 17 kecamatan di Karanganyar.
     */
    public function index(Request $request)
    {
        $districts = DistrictStatistic::orderBy('name', 'asc')->get();

        $totalPopulation = $districts->sum('population');
        $totalArea = $districts->sum('area_sqkm');
        $totalVillages = $districts->sum('villages_count');
        $avgDensity = $totalArea > 0 ? round($totalPopulation / $totalArea) : 0;

        return view('districts.index', compact(
            'districts',
            'totalPopulation',
            'totalArea',
            'totalVillages',
            'avgDensity'
        ));
    }
}
