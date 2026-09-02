@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    {{-- Welcome Banner (Official BPS Corporate Navy & Orange) --}}
    <div class="bg-gradient-to-r from-[#04325e] via-[#004b87] to-[#013a63] rounded-3xl p-6 sm:p-8 text-white border-b-4 border-[#f7941d] shadow-md flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-bold text-slate-100 border border-white/20 mb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>BPS Kabupaten Karanganyar • Portal PST</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Selamat Datang, {{ auth()->user()->name }}!</h1>
            <p class="mt-2 text-xs sm:text-sm text-blue-100 max-w-xl leading-relaxed">
                Pantau antrean percakapan masyarakat, tindak lanjuti tiket aduan masuk, dan kelola basis data statistik secara langsung.
            </p>
        </div>
        <div class="flex flex-wrap gap-2.5 shrink-0">
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition-all shadow-xs border border-white/20 flex items-center gap-2">
                <span class="iconify text-base text-[#f7941d]" data-icon="lucide:printer"></span>
                <span>Cetak Laporan PDF</span>
            </a>
            <a href="{{ route('admin.conversations.index', ['status' => 'waiting']) }}" class="px-4 py-2.5 rounded-xl bg-[#f7941d] hover:bg-[#e07e0c] text-white text-xs font-black transition-all shadow-sm flex items-center gap-2">
                <span class="iconify text-base" data-icon="lucide:messages-square"></span>
                <span>Cek Antrean Chat</span>
            </a>
            <a href="{{ route('admin.complaints.index', ['status' => 'new']) }}" class="px-4 py-2.5 rounded-xl bg-[#00a651] hover:bg-[#008d36] text-white text-xs font-black transition-all shadow-sm flex items-center gap-2">
                <span class="iconify text-base" data-icon="lucide:ticket"></span>
                <span>Aduan Baru</span>
            </a>
        </div>
    </div>

    {{-- Live Statistics Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Percakapan Hari Ini --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-2.5">
                <span class="text-xs font-semibold text-slate-400">Hari Ini</span>
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <span class="iconify text-base" data-icon="lucide:messages-square"></span>
                </div>
            </div>
            <p id="stat-conversations-today" class="text-2xl font-black text-slate-900">{{ $stats['conversations_today'] }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Chat Masuk</p>
        </div>

        {{-- Menunggu Petugas --}}
        <a href="{{ route('admin.conversations.index', ['status' => 'waiting']) }}"
           class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md hover:border-amber-300 transition-all group">
            <div class="flex items-center justify-between mb-2.5">
                <span class="text-xs font-semibold text-amber-600">Antrean</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="iconify text-base" data-icon="lucide:clock"></span>
                </div>
            </div>
            <p id="stat-conversations-waiting" class="text-2xl font-black text-amber-600">{{ $stats['conversations_waiting'] }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Menunggu Petugas</p>
        </a>

        {{-- Aduan Baru --}}
        <a href="{{ route('admin.complaints.index', ['status' => 'new']) }}"
           class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md hover:border-rose-300 transition-all group">
            <div class="flex items-center justify-between mb-2.5">
                <span class="text-xs font-semibold text-rose-600">Tiket Aduan</span>
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="iconify text-base" data-icon="lucide:ticket"></span>
                </div>
            </div>
            <p id="stat-complaints-new" class="text-2xl font-black text-rose-600">{{ $stats['complaints_new'] }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Aduan Baru</p>
        </a>

        {{-- Basis Pengetahuan --}}
        <a href="{{ route('admin.articles.index') }}"
           class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md hover:border-slate-300 transition-all group">
            <div class="flex items-center justify-between mb-2.5">
                <span class="text-xs font-semibold text-slate-500">Database</span>
                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="iconify text-base" data-icon="lucide:book-open"></span>
                </div>
            </div>
            <p class="text-2xl font-black text-slate-900">{{ $stats['total_articles'] }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Artikel FAQ Aktif</p>
        </a>
    </div>

    {{-- Interactive Analytics & Charts Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Grafik Tren 7 Hari Terakhir (2 Kolom) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100 mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <span class="iconify text-lg" data-icon="lucide:trending-up"></span>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm sm:text-base">Tren Aktivitas Layanan (7 Hari Terakhir)</h3>
                        <p class="text-[11px] text-slate-400">Volume percakapan chatbot vs tiket pengaduan masyarakat</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 text-xs font-semibold">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                        <span class="text-slate-600">Percakapan Chat</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                        <span class="text-slate-600">Tiket Aduan</span>
                    </div>
                </div>
            </div>
            <div class="h-64 sm:h-72 w-full">
                <canvas id="trendActivityChart"></canvas>
            </div>
        </div>

        {{-- Grafik Distribusi Status Pengaduan (1 Kolom) --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <span class="iconify text-lg" data-icon="lucide:pie-chart"></span>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm sm:text-base">Status Tiket Aduan</h3>
                            <p class="text-[11px] text-slate-400">Proporsi progres penanganan aduan</p>
                        </div>
                    </div>
                </div>
                <div class="h-48 sm:h-52 w-full relative flex items-center justify-center">
                    <canvas id="complaintStatusChart"></canvas>
                </div>
            </div>

            {{-- Summary Pills --}}
            <div class="grid grid-cols-3 gap-2 pt-4 border-t border-slate-100 text-center">
                <div class="p-2 rounded-xl bg-red-50/80 border border-red-100">
                    <p class="text-[10px] font-bold text-red-700 uppercase">Baru</p>
                    <p class="text-sm font-black text-red-800 mt-0.5">{{ $complaintDistribution['new'] }}</p>
                </div>
                <div class="p-2 rounded-xl bg-amber-50/80 border border-amber-100">
                    <p class="text-[10px] font-bold text-amber-700 uppercase">Proses</p>
                    <p class="text-sm font-black text-amber-800 mt-0.5">{{ $complaintDistribution['processing'] }}</p>
                </div>
                <div class="p-2 rounded-xl bg-emerald-50/80 border border-emerald-100">
                    <p class="text-[10px] font-bold text-emerald-700 uppercase">Selesai</p>
                    <p class="text-sm font-black text-emerald-800 mt-0.5">{{ $complaintDistribution['resolved'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Main 2 Column Grid: Live Chats & Recent Complaints --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Percakapan Terkini --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <span class="iconify text-lg" data-icon="lucide:messages-square"></span>
                        </div>
                        <h3 class="font-bold text-slate-800">Percakapan Live Terkini</h3>
                    </div>
                    <a href="{{ route('admin.conversations.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">
                        Lihat Semua
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($recentConversations as $rc)
                    @php
                    $lastMessage = $rc->messages->first();
                    $badge = [
                        'waiting' => 'bg-amber-100 text-amber-800 border-amber-300 animate-pulse',
                        'handled' => 'bg-blue-100 text-blue-800 border-blue-300',
                        'bot' => 'bg-slate-100 text-slate-700 border-slate-200',
                        'closed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                    ];
                    @endphp
                    <a href="{{ route('admin.conversations.show', $rc) }}" class="flex items-start justify-between gap-3 p-3.5 rounded-xl bg-slate-50 hover:bg-blue-50/60 border border-slate-200/70 hover:border-blue-200 transition-all group">
                        <div class="truncate">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="font-bold text-xs text-slate-900 truncate">{{ $rc->visitor_name }}</h4>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $badge[$rc->status] ?? '' }}">
                                    {{ $rc->status === 'waiting' ? 'Menunggu' : ($rc->status === 'handled' ? 'Petugas' : ($rc->status === 'closed' ? 'Selesai' : 'Otomatis')) }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 truncate">
                                {{ $lastMessage ? $lastMessage->content : 'Sesi dimulai' }}
                            </p>
                        </div>
                        <span class="text-[10px] text-slate-400 shrink-0 mt-0.5">
                            {{ $rc->last_message_at ? $rc->last_message_at->diffForHumans() : $rc->created_at->diffForHumans() }}
                        </span>
                    </a>
                    @empty
                    <p class="text-center py-8 text-xs text-slate-400">Belum ada percakapan masuk.</p>
                    @endforelse
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Layanan Konsultasi Aktif 24 Jam</span>
                </span>
                <a href="{{ route('chat.index') }}" target="_blank" class="text-blue-600 font-semibold hover:underline">
                    Buka Halaman Chat
                </a>
            </div>
        </div>

        {{-- Aduan Masuk Terkini --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                            <span class="iconify text-lg" data-icon="lucide:ticket"></span>
                        </div>
                        <h3 class="font-bold text-slate-800">Aduan Masuk Terkini</h3>
                    </div>
                    <a href="{{ route('admin.complaints.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">
                        Lihat Semua
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($recentComplaints as $comp)
                    @php
                    $compBadge = [
                        'new' => 'bg-red-50 text-red-700 border-red-200',
                        'verified' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'processing' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'rejected' => 'bg-slate-100 text-slate-600 border-slate-200',
                    ];
                    $compLabel = [
                        'new' => 'Baru',
                        'verified' => 'Diverifikasi',
                        'processing' => 'Diproses',
                        'resolved' => 'Selesai',
                        'rejected' => 'Ditolak',
                    ];
                    @endphp
                    <a href="{{ route('admin.complaints.show', $comp) }}" class="flex items-start justify-between gap-3 p-3.5 rounded-xl bg-slate-50 hover:bg-rose-50/40 border border-slate-200/70 hover:border-rose-200 transition-all group">
                        <div class="truncate">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-mono font-bold text-xs text-blue-600">{{ $comp->ticket_number }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $compBadge[$comp->status] ?? '' }}">
                                    {{ $compLabel[$comp->status] ?? $comp->status }}
                                </span>
                            </div>
                            <h4 class="font-semibold text-xs text-slate-800 truncate">{{ $comp->reporter_name }} • <span class="text-slate-500 font-normal capitalize">{{ $comp->category }}</span></h4>
                            <p class="text-xs text-slate-500 truncate mt-0.5">{{ $comp->description }}</p>
                        </div>
                        <span class="text-[10px] text-slate-400 shrink-0 mt-0.5">
                            {{ $comp->created_at->diffForHumans() }}
                        </span>
                    </a>
                    @empty
                    <p class="text-center py-8 text-xs text-slate-400">Belum ada aduan masuk.</p>
                    @endforelse
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                <span class="flex items-center gap-1.5">
                    <span class="iconify text-emerald-600 text-sm" data-icon="lucide:shield-check"></span>
                    <span>Tingkat Kepuasan Layanan: <strong>{{ $satisfactionRate }}%</strong> ({{ $feedbackHelpful }}/{{ $feedbackTotal }} ulasan)</span>
                </span>
                <a href="{{ route('aduan.create') }}" target="_blank" class="text-blue-600 font-semibold hover:underline">
                    Form Aduan Publik
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

{{-- Dashboard Data in JSON Script tag to avoid linter parse errors --}}
<script id="dashboard-chart-data" type="application/json">
{
    "chartLabels": @json($chartLabels),
    "chartConversations": @json($chartConversations),
    "chartComplaints": @json($chartComplaints),
    "complaintDistribution": @json($complaintDistribution),
    "statsUrl": @json(route('admin.dashboard.stats'))
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const rawData = document.getElementById('dashboard-chart-data');
    const dashboardData = rawData ? JSON.parse(rawData.textContent) : {};

    // 1. Line Chart: Tren Aktivitas 7 Hari Terakhir
    const trendCanvas = document.getElementById('trendActivityChart');
    if (trendCanvas) {
        const trendCtx = trendCanvas.getContext('2d');
        
        // Gradient fills
        const blueGradient = trendCtx.createLinearGradient(0, 0, 0, 300);
        blueGradient.addColorStop(0, 'rgba(37, 99, 235, 0.25)');
        blueGradient.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

        const amberGradient = trendCtx.createLinearGradient(0, 0, 0, 300);
        amberGradient.addColorStop(0, 'rgba(245, 158, 11, 0.25)');
        amberGradient.addColorStop(1, 'rgba(245, 158, 11, 0.0)');

        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: dashboardData.chartLabels || [],
                datasets: [
                    {
                        label: 'Percakapan Chat',
                        data: dashboardData.chartConversations || [],
                        borderColor: '#2563eb',
                        backgroundColor: blueGradient,
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Tiket Aduan',
                        data: dashboardData.chartComplaints || [],
                        borderColor: '#f59e0b',
                        backgroundColor: amberGradient,
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 12, family: 'Outfit, sans-serif' },
                        bodyFont: { size: 12, family: 'Plus Jakarta Sans, sans-serif' },
                        padding: 10,
                        cornerRadius: 10
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { size: 10, family: 'Plus Jakarta Sans' },
                            color: '#94a3b8'
                        },
                        grid: {
                            color: '#f1f5f9'
                        },
                        border: { dash: [4, 4] }
                    },
                    x: {
                        ticks: {
                            font: { size: 10, family: 'Plus Jakarta Sans' },
                            color: '#94a3b8'
                        },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 2. Doughnut Chart: Distribusi Status Aduan
    const statusCanvas = document.getElementById('complaintStatusChart');
    if (statusCanvas) {
        const statusCtx = statusCanvas.getContext('2d');
        const dist = dashboardData.complaintDistribution || { new: 0, processing: 0, resolved: 0 };
        const newCount = Number(dist.new) || 0;
        const procCount = Number(dist.processing) || 0;
        const resCount = Number(dist.resolved) || 0;
        const totalAduan = newCount + procCount + resCount;

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Baru', 'Diproses', 'Selesai'],
                datasets: [{
                    data: totalAduan > 0 ? [newCount, procCount, resCount] : [1, 0, 0],
                    backgroundColor: totalAduan > 0 ? ['#ef4444', '#f59e0b', '#10b981'] : ['#e2e8f0', '#e2e8f0', '#e2e8f0'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { size: 11, family: 'Plus Jakarta Sans' },
                            boxWidth: 10,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        enabled: totalAduan > 0,
                        backgroundColor: '#0f172a',
                        padding: 8,
                        cornerRadius: 8
                    }
                }
            }
        });
    }

    // Real-time counter updater on dashboard
    if (dashboardData.statsUrl) {
        function updateDashboardStats() {
            fetch(dashboardData.statsUrl)
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    const todayEl = document.getElementById('stat-conversations-today');
                    const waitEl = document.getElementById('stat-conversations-waiting');
                    const newCompEl = document.getElementById('stat-complaints-new');

                    if (todayEl && data.conversations_today !== undefined) todayEl.textContent = data.conversations_today;
                    if (waitEl && data.conversations_waiting !== undefined) waitEl.textContent = data.conversations_waiting;
                    if (newCompEl && data.complaints_new !== undefined) newCompEl.textContent = data.complaints_new;
                })
                .catch(function () {});
        }

        setInterval(updateDashboardStats, 5000);
    }
});
</script>
@endpush
