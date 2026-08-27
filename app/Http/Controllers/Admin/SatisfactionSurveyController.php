<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SatisfactionSurvey;
use Illuminate\Http\Request;

class SatisfactionSurveyController extends Controller
{
    /**
     * Tampilkan analisis indeks kepuasan masyarakat dan rekapitulasi evaluasi layanan.
     */
    public function index(Request $request)
    {
        $totalSurveys = SatisfactionSurvey::count();

        $avgQuality = $totalSurveys > 0 ? round(SatisfactionSurvey::avg('quality_score'), 2) : 5.0;
        $avgSpeed = $totalSurveys > 0 ? round(SatisfactionSurvey::avg('speed_score'), 2) : 5.0;
        $avgFriendliness = $totalSurveys > 0 ? round(SatisfactionSurvey::avg('friendliness_score'), 2) : 5.0;
        $avgFacility = $totalSurveys > 0 ? round(SatisfactionSurvey::avg('facility_score'), 2) : 5.0;

        $overallAverage = $totalSurveys > 0 ? round(SatisfactionSurvey::avg('overall_score'), 2) : 5.0;
        $ikmScore = round(($overallAverage / 5) * 100, 2);

        // Menentukan Predikat Mutu IKM
        $grade = 'A (Sangat Baik)';
        if ($ikmScore < 65.0) {
            $grade = 'D (Tidak Baik)';
        } elseif ($ikmScore < 76.61) {
            $grade = 'C (Kurang Baik)';
        } elseif ($ikmScore < 88.31) {
            $grade = 'B (Baik)';
        }

        $surveys = SatisfactionSurvey::orderByDesc('created_at')->paginate(15);

        return view('admin.surveys.index', compact(
            'totalSurveys',
            'avgQuality',
            'avgSpeed',
            'avgFriendliness',
            'avgFacility',
            'overallAverage',
            'ikmScore',
            'grade',
            'surveys'
        ));
    }
}
