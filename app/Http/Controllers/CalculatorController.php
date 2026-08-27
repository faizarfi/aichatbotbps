<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    /**
     * Tampilkan halaman kalkulator statistik interaktif.
     */
    public function index()
    {
        // Indeks Harga Konsumen (IHK) Historis Karanganyar / Surakarta
        $ihkData = [
            '2018' => 100.00,
            '2019' => 103.15,
            '2020' => 104.82,
            '2021' => 106.50,
            '2022' => 112.45,
            '2023' => 115.80,
            '2024' => 118.95,
        ];

        return view('calculators.index', compact('ihkData'));
    }
}
