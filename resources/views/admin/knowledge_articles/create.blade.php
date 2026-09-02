@extends('layouts.admin')

@section('title', 'Tambah Artikel')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Tambah Artikel FAQ Baru</h2>
            <p class="text-sm text-slate-500 mt-1">Lengkapi informasi dan jawaban resmi agar bot dapat menjawab secara akurat.</p>
        </div>
        <a href="{{ route('admin.articles.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1.5">
            <span class="iconify" data-icon="lucide:arrow-left"></span> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.articles.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="knowledge_category_id" class="block text-sm font-semibold text-slate-700 mb-2">Kategori Layanan <span class="text-rose-500">*</span></label>
                    <select id="knowledge_category_id" name="knowledge_category_id" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('knowledge_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('knowledge_category_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="title" class="block text-sm font-semibold text-slate-700 mb-2">Judul Artikel <span class="text-rose-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title', request('title')) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                           placeholder="Contoh: Cara Permintaan Data Statistik">
                    @error('title') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="question" class="block text-sm font-semibold text-slate-700 mb-2">Pertanyaan / Pertanyaan Terkait <span class="text-rose-500">*</span></label>
                <textarea id="question" name="question" rows="2" required
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none"
                          placeholder="Pertanyaan umum yang biasa diajukan masyarakat...">{{ old('question', request('question')) }}</textarea>
                @error('question') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="answer" class="block text-sm font-semibold text-slate-700 mb-2">Jawaban Resmi <span class="text-rose-500">*</span></label>
                <textarea id="answer" name="answer" rows="6" required
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                          placeholder="Tuliskan jawaban yang detail, terstruktur, dan jelas...">{{ old('answer') }}</textarea>
                <p class="text-xs text-slate-400 mt-1">Jawaban ini yang akan langsung disampaikan oleh chatbot kepada pengunjung.</p>
                @error('answer') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="keywords" class="block text-sm font-semibold text-slate-700 mb-2">Kata Kunci / Sinonim (Pisahkan dengan koma)</label>
                <input type="text" id="keywords" name="keywords" value="{{ old('keywords') }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                       placeholder="Contoh: minta data, unduh publikasi, skripsi, pdrb, kemiskinan">
                <p class="text-xs text-slate-400 mt-1">Kata kunci membantu mesin pencari bot menemukan artikel meskipun pertanyaan memiliki ejaan bervariasi.</p>
                @error('keywords') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                <div>
                    <label for="source_title" class="block text-sm font-semibold text-slate-700 mb-2">Nama Sumber Resmi</label>
                    <input type="text" id="source_title" name="source_title" value="{{ old('source_title', 'Standar Pelayanan BPS Kabupaten Karanganyar') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                           placeholder="Contoh: Website Resmi BPS Karanganyar">
                    @error('source_title') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="source_url" class="block text-sm font-semibold text-slate-700 mb-2">URL Tautan Sumber</label>
                    <input type="url" id="source_url" name="source_url" value="{{ old('source_url', 'https://karanganyarkab.bps.go.id') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                           placeholder="https://karanganyarkab.bps.go.id">
                    @error('source_url') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium text-slate-700">Aktifkan artikel ini untuk digunakan oleh chatbot</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.articles.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-md shadow-blue-600/20 transition-all">
                    Simpan Artikel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
