@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Pengguna & Petugas</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola akun administrator dan petugas operasional layanan.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-all shadow-md shadow-blue-600/20 shrink-0">
            <span class="iconify text-lg" data-icon="lucide:user-plus"></span>
            <span>Tambah Pengguna</span>
        </a>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Nama & Email</th>
                        <th class="px-6 py-4 text-center">Peran (Role)</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4">Login Terakhir</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $u)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 font-bold text-sm flex items-center justify-center">
                                    {{ substr($u->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 leading-tight">{{ $u->name }}</h4>
                                    <span class="text-xs text-slate-400 font-mono">{{ $u->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $u->role === 'admin' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }} capitalize">
                                {{ $u->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($u->id !== auth()->id())
                            <form action="{{ route('admin.users.toggle', $u) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer transition-all {{ $u->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $u->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                    {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Anda
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500">
                            {{ $u->last_login_at ? $u->last_login_at->format('d M Y, H:i') : 'Belum pernah login' }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-1.5">
                            <a href="{{ route('admin.users.edit', $u) }}" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg inline-block transition-colors" title="Edit">
                                <span class="iconify text-base" data-icon="lucide:edit-3"></span>
                            </a>
                            @if($u->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="inline-block" onsubmit="return confirmFormAction(this, 'Hapus Pengguna?', 'Akun pengguna ini akan dihapus secara permanen.', 'Ya, Hapus')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                    <span class="iconify text-base" data-icon="lucide:trash-2"></span>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
