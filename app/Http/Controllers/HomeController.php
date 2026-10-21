<?php

namespace App\Http\Controllers;

use App\Services\BpsPublicationService;

class HomeController extends Controller
{
    public function __construct(
        protected BpsPublicationService $publicationService
    ) {}

    /**
     * Tampilkan halaman beranda dengan katalog publikasi resmi BPS Karanganyar.
     */
    public function index()
    {
        $featuredPublications = $this->publicationService->getFeaturedPublications(6);
        $latestPublications = $this->publicationService->getLatestPublications(8);
        $publicationStats = $this->publicationService->getStatistics();

        return view('home', compact('featuredPublications', 'latestPublications', 'publicationStats'));
    }
}
