<?php

use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\ConversationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DataRequestController as AdminDataRequestController;
use App\Http\Controllers\Admin\KnowledgeArticleController;
use App\Http\Controllers\Admin\KnowledgeCategoryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\SatisfactionSurveyController as AdminSatisfactionSurveyController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DataRequestController;
use App\Http\Controllers\DistrictStatisticController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicChatController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SatisfactionSurveyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Publik
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Chatbot Publik
Route::get('/chat', [PublicChatController::class, 'index'])->name('chat.index');
Route::get('/chat/messages', [PublicChatController::class, 'messages'])->name('chat.messages');
Route::post('/chat/message', [PublicChatController::class, 'store'])
    ->middleware('throttle:60,1')
    ->name('chat.message');
Route::post('/chat/request-officer', [PublicChatController::class, 'requestOfficer'])
    ->middleware('throttle:15,1')
    ->name('chat.request-officer');
Route::post('/chat/feedback', [PublicChatController::class, 'feedback'])
    ->middleware('throttle:30,1')
    ->name('chat.feedback');

// Layanan Aduan (Wajib Login untuk Mengajukan)
Route::middleware('auth')->group(function () {
    Route::get('/aduan', [ComplaintController::class, 'create'])->name('aduan.create');
    Route::post('/aduan', [ComplaintController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('aduan.store');
});
Route::get('/status-aduan', [ComplaintController::class, 'status'])
    ->middleware('throttle:20,1')
    ->name('status-aduan');

// Peta Tematik 17 Kecamatan Karanganyar
Route::get('/peta-statistik', [DistrictStatisticController::class, 'index'])->name('districts.index');

// Kalkulator Statistik Interaktif
Route::get('/kalkulator-statistik', [CalculatorController::class, 'index'])->name('calculators.index');

// Reservasi Konsultasi Tatap Muka PST (Wajib Login untuk Mengajukan)
Route::middleware('auth')->group(function () {
    Route::get('/reservasi', [ReservationController::class, 'create'])->name('reservasi.create');
    Route::post('/reservasi', [ReservationController::class, 'store'])->name('reservasi.store');
});
Route::get('/reservasi/tiket/{code}', [ReservationController::class, 'ticket'])->name('reservasi.ticket');
Route::get('/lacak-reservasi', [ReservationController::class, 'track'])->name('reservasi.track');

// Permohonan Data Mikro & Rekomendasi Statistik (Wajib Login untuk Mengajukan)
Route::middleware('auth')->group(function () {
    Route::get('/layanan-data', [DataRequestController::class, 'create'])->name('layanan-data.create');
    Route::post('/layanan-data', [DataRequestController::class, 'store'])->name('layanan-data.store');
});
Route::get('/lacak-layanan-data', [DataRequestController::class, 'track'])->name('layanan-data.track');
Route::get('/layanan-data/download/{dataRequest}', [DataRequestController::class, 'downloadResult'])->name('layanan-data.download');

// Survei Kepuasan Masyarakat (Wajib Login untuk Mengisi)
Route::middleware('auth')->group(function () {
    Route::get('/survei-kepuasan', [SatisfactionSurveyController::class, 'create'])->name('survei.create');
    Route::post('/survei-kepuasan', [SatisfactionSurveyController::class, 'store'])->name('survei.store');
});
Route::get('/survei-kepuasan/sukses/{survey}', [SatisfactionSurveyController::class, 'success'])->name('survei.success');

// Profil Pengguna Masyarakat (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::get('/profil-saya', [MyProfileController::class, 'show'])->name('my-profile.show');
    Route::patch('/profil-saya', [MyProfileController::class, 'update'])->name('my-profile.update');
    Route::delete('/profil-saya', [MyProfileController::class, 'destroy'])->name('my-profile.destroy');
});

// Kebijakan Privasi
Route::get('/kebijakan-privasi', function () {
    return view('kebijakan-privasi');
})->name('kebijakan-privasi');

/*
|--------------------------------------------------------------------------
| Autentikasi Laravel Breeze & Password Reset
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

// Redirect alias /admin/login ke /login
Route::get('/admin/login', function () {
    return redirect()->route('login');
})->name('admin.login');

/*
|--------------------------------------------------------------------------
| Area Terproteksi Admin & Petugas BPS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,petugas'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getLiveStats'])->name('admin.dashboard.stats');

    // Profil Pengguna Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Basis Pengetahuan - Kategori
    Route::resource('categories', KnowledgeCategoryController::class, ['as' => 'admin'])->except(['show']);

    // Basis Pengetahuan - Artikel
    Route::resource('articles', KnowledgeArticleController::class, ['as' => 'admin'])->except(['show']);
    Route::patch('articles/{article}/toggle', [KnowledgeArticleController::class, 'toggleActive'])->name('admin.articles.toggle');

    // Percakapan Live & Antrean
    Route::get('/percakapan', [ConversationController::class, 'index'])->name('admin.conversations.index');
    Route::get('/percakapan/{conversation}', [ConversationController::class, 'show'])->name('admin.conversations.show');
    Route::get('/percakapan/{conversation}/messages', [ConversationController::class, 'getMessages'])->name('admin.conversations.messages');
    Route::post('/percakapan/{conversation}/reply', [ConversationController::class, 'reply'])->name('admin.conversations.reply');
    Route::post('/percakapan/{conversation}/takeover', [ConversationController::class, 'takeOver'])->name('admin.conversations.takeover');
    Route::post('/percakapan/{conversation}/close', [ConversationController::class, 'close'])->name('admin.conversations.close');

    // Pengelolaan Aduan
    Route::get('/aduan', [AdminComplaintController::class, 'index'])->name('admin.complaints.index');
    Route::get('/aduan/{complaint}', [AdminComplaintController::class, 'show'])->name('admin.complaints.show');
    Route::post('/aduan/{complaint}/status', [AdminComplaintController::class, 'updateStatus'])->name('admin.complaints.status');
    Route::get('/aduan/lampiran/{attachment}', [AdminComplaintController::class, 'downloadAttachment'])->name('admin.complaints.download');

    // Manajemen Reservasi Tatap Muka
    Route::get('/reservasi', [AdminReservationController::class, 'index'])->name('admin.reservations.index');
    Route::get('/reservasi/{reservation}', [AdminReservationController::class, 'show'])->name('admin.reservations.show');
    Route::post('/reservasi/{reservation}/status', [AdminReservationController::class, 'updateStatus'])->name('admin.reservations.status');

    // Pengelolaan Permohonan Data Mikro & ROMANTIK
    Route::get('/permintaan-data', [AdminDataRequestController::class, 'index'])->name('admin.data-requests.index');
    Route::get('/permintaan-data/{dataRequest}', [AdminDataRequestController::class, 'show'])->name('admin.data-requests.show');
    Route::post('/permintaan-data/{dataRequest}/status', [AdminDataRequestController::class, 'updateStatus'])->name('admin.data-requests.status');
    Route::get('/permintaan-data/lampiran/{dataRequest}', [AdminDataRequestController::class, 'downloadAttachment'])->name('admin.data-requests.download');

    // Laporan Survei Kepuasan Masyarakat (IKM / SKM)
    Route::get('/survei', [AdminSatisfactionSurveyController::class, 'index'])->name('admin.surveys.index');

    // Laporan & Rekapitulasi PDF
    Route::get('/laporan', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/laporan/pdf', [ReportController::class, 'downloadPdf'])->name('admin.reports.pdf');
    Route::get('/laporan/preview', [ReportController::class, 'previewPdf'])->name('admin.reports.preview');

    // Pengelolaan Pengguna (Hanya Admin)
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class, ['as' => 'admin'])->except(['show']);
        Route::patch('users/{user}/toggle', [UserController::class, 'toggleActive'])->name('admin.users.toggle');
    });
});
