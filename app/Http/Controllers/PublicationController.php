<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Services\BpsPublicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicationController extends Controller
{
    public function __construct(
        protected BpsPublicationService $publicationService
    ) {}

    /**
     * Halaman Katalog Seluruh Publikasi Resmi BPS Kabupaten Karanganyar.
     */
    public function index(Request $request)
    {
        $query = Publication::query();

        // Filter kata kunci
        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('abstract', 'like', "%{$keyword}%")
                  ->orWhere('issn', 'like', "%{$keyword}%");
            });
        }

        // Filter tahun
        if ($request->filled('year')) {
            $year = (int) $request->input('year');
            $query->whereYear('rl_date', $year);
        }

        // Filter kategori (KDA / Kecamatan / Lainnya)
        if ($request->filled('category')) {
            $category = $request->input('category');
            if ($category === 'kda') {
                $query->where('title', 'like', '%Kabupaten Karanganyar Dalam Angka%');
            } elseif ($category === 'kecamatan') {
                $query->where('title', 'like', '%Kecamatan%Dalam Angka%');
            } elseif ($category === 'statistik_daerah') {
                $query->where('title', 'like', '%Statistik Daerah%');
            }
        }

        $publications = $query->orderBy('rl_date', 'desc')
            ->paginate(12)
            ->withQueryString();

        $stats = $this->publicationService->getStatistics();
        $availableYears = Publication::selectRaw('YEAR(rl_date) as year')
            ->whereNotNull('rl_date')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('publications.index', compact('publications', 'stats', 'availableYears'));
    }

    /**
     * Detail satu publikasi.
     */
    public function show(string $slugOrId)
    {
        $publication = Publication::where('slug', $slugOrId)
            ->orWhere('pub_id', $slugOrId)
            ->firstOrFail();

        $related = Publication::where('id', '!=', $publication->id)
            ->where(function ($q) use ($publication) {
                $q->where('title', 'like', '%' . Str::before($publication->title, ' ') . '%');
            })
            ->latest('rl_date')
            ->take(4)
            ->get();

        return view('publications.show', compact('publication', 'related'));
    }
}
