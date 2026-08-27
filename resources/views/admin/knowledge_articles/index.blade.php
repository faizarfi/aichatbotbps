@extends('layouts.admin')

@section('title', 'Artikel & FAQ')

@section('content')
<div class="space-y-6">
    {{-- Header & Action --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Basis Pengetahuan & FAQ</h2>
            <p class="text-sm text-slate-500 mt-1">Artikel dan jawaban resmi yang dijadikan sumber rujukan chatbot.</p>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-all shadow-md shadow-blue-600/20 shrink-0">
            <span class="iconify text-lg" data-icon="lucide:plus"></span>
            <span>Tambah Artikel</span>
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.articles.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                       placeholder="Cari judul, pertanyaan, jawaban...">
            </div>
            <div>
                <select name="category_id" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="status" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold rounded-xl transition-colors flex items-center justify-center gap-1.5">
                    <span class="iconify" data-icon="lucide:filter"></span> Filter
                </button>
                @if(request()->hasAny(['search', 'category_id', 'status']))
                <a href="{{ route('admin.articles.index') }}" class="px-3 py-2 border border-slate-300 hover:bg-slate-50 text-slate-600 rounded-xl text-sm font-medium transition-colors" title="Reset">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Articles List --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Judul & Pertanyaan</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Kata Kunci (Keywords)</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($articles as $article)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <h4 class="font-bold text-slate-900 leading-snug">{{ $article->title }}</h4>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-1"><span class="font-medium text-slate-700">T:</span> {{ $article->question }}</p>
                            @if($article->source_title)
                            <span class="inline-flex items-center gap-1 text-[11px] text-blue-600 mt-1">
                                <span class="iconify" data-icon="lucide:link-2"></span> {{ $article->source_title }}
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                {{ $article->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 max-w-xs">
                                @foreach((array)($article->keywords ?? []) as $kw)
                                <span class="px-2 py-0.5 rounded text-[11px] bg-blue-50 text-blue-700 border border-blue-100 font-mono">{{ $kw }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('admin.articles.toggle', $article) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer transition-all {{ $article->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $article->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                    {{ $article->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right space-x-1.5">
                            <a href="{{ route('admin.articles.edit', $article) }}" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg inline-block transition-colors" title="Edit">
                                <span class="iconify text-base" data-icon="lucide:edit-3"></span>
                            </a>
                            <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="inline-block" onsubmit="return confirmFormAction(this, 'Hapus Artikel FAQ?', 'Artikel ini tidak akan lagi dapat dicari oleh chatbot.', 'Ya, Hapus')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                    <span class="iconify text-base" data-icon="lucide:trash-2"></span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <span class="iconify text-4xl mx-auto mb-2 text-slate-300" data-icon="lucide:file-question"></span>
                            Tidak ada artikel yang sesuai kriteria pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($articles->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $articles->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
