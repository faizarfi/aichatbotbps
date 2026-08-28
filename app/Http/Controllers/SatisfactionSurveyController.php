<?php

namespace App\Http\Controllers;

use App\Models\SatisfactionSurvey;
use Illuminate\Http\Request;

class SatisfactionSurveyController extends Controller
{
    /**
     * Tampilkan form pengisian survei kepuasan masyarakat.
     */
    public function create()
    {
        return view('surveys.create');
    }

    /**
     * Simpan evaluasi penilaian survei kepuasan masyarakat.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'respondent_name' => 'nullable|string|max:255',
            'respondent_email' => 'nullable|email|max:255',
            'respondent_phone' => 'nullable|string|max:25',
            'respondent_type' => 'required|string|max:50',
            'service_used' => 'required|string|max:50',
            'quality_score' => 'required|integer|min:1|max:5',
            'speed_score' => 'required|integer|min:1|max:5',
            'friendliness_score' => 'required|integer|min:1|max:5',
            'facility_score' => 'required|integer|min:1|max:5',
            'feedback_text' => 'nullable|string|max:1000',
        ]);

        $overall = round(($validated['quality_score'] + $validated['speed_score'] + $validated['friendliness_score'] + $validated['facility_score']) / 4, 2);

        $survey = SatisfactionSurvey::create([
            'user_id' => auth()->id(),
            'respondent_name' => $validated['respondent_name'] ?: 'Masyarakat Umum (Anonim)',
            'respondent_email' => $validated['respondent_email'],
            'respondent_phone' => $validated['respondent_phone'],
            'respondent_type' => $validated['respondent_type'],
            'service_used' => $validated['service_used'],
            'quality_score' => $validated['quality_score'],
            'speed_score' => $validated['speed_score'],
            'friendliness_score' => $validated['friendliness_score'],
            'facility_score' => $validated['facility_score'],
            'overall_score' => $overall,
            'feedback_text' => $validated['feedback_text'],
        ]);

        return redirect()->route('survei.success', $survey)
            ->with('success', 'Terima kasih! Penilaian Anda sangat berharga bagi peningkatan mutu pelayanan publik BPS Kabupaten Karanganyar.');
    }

    /**
     * Tampilan konfirmasi terima kasih survei.
     */
    public function success(SatisfactionSurvey $survey)
    {
        return view('surveys.success', compact('survey'));
    }
}
