@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="mb-2 flex items-center gap-2 text-sm text-slate-500">
                <i class="fas fa-users text-rose-600"></i><span>Manajemen User</span><i class="fas fa-chevron-right text-xs text-slate-400"></i><span>Belum Bayar</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">User Belum Bayar</h1>
            <p class="mt-1 text-sm text-slate-500">Daftar student yang belum memiliki membership.</p>
        </div>
        <a href="{{ route('students.paid') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700"><i class="fas fa-user-check"></i> Lihat Sudah Bayar</a>
    </div>

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5"><div class="flex items-center justify-between"><p class="text-xs font-medium text-slate-500 sm:text-sm">Total Belum Bayar</p><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-50 text-rose-600"><i class="fas fa-user-clock"></i></span></div><p class="mt-3 text-2xl font-bold text-slate-900 sm:text-3xl">{{ number_format($unpaidStudentCount) }}</p></div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5"><div class="flex items-center justify-between"><p class="text-xs font-medium text-slate-500 sm:text-sm">Tanpa Membership</p><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600"><i class="fas fa-id-card-clip"></i></span></div><p class="mt-3 text-2xl font-bold text-slate-900 sm:text-3xl">{{ number_format($unpaidStudentCount) }}</p></div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5"><div class="flex items-center justify-between"><p class="text-xs font-medium text-slate-500 sm:text-sm">Halaman Ini</p><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-50 text-violet-600"><i class="fas fa-list"></i></span></div><p class="mt-3 text-2xl font-bold text-slate-900 sm:text-3xl">{{ $students->count() }}</p><p class="mt-1 text-xs text-slate-400">dari {{ $students->total() }} hasil</p></div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5"><div class="flex items-center justify-between"><p class="text-xs font-medium text-slate-500 sm:text-sm">Perlu Follow Up</p><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 text-amber-600"><i class="fas fa-bell"></i></span></div><p class="mt-3 text-2xl font-bold text-slate-900 sm:text-3xl">{{ number_format($unpaidStudentCount) }}</p><p class="mt-1 text-xs text-slate-400">belum memiliki membership</p></div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        <form action="{{ route('students.unpaid') }}" method="GET" class="flex flex-col gap-3 sm:flex-row">
            <div class="relative flex-1"><i class="fas fa-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i><input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau perusahaan..." class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-3 text-sm outline-none transition focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-100"></div>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700"><i class="fas fa-search"></i> Cari</button>
            @if(request()->filled('search'))<a href="{{ route('students.unpaid') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"><i class="fas fa-rotate-left"></i> Reset</a>@endif
        </form>
    </div>

    <div class="hidden overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm md:block">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4"><div><h2 class="font-semibold text-slate-900">Daftar User Belum Bayar</h2><p class="mt-1 text-xs text-slate-500">Menampilkan {{ $students->count() }} dari {{ $students->total() }} user</p></div><span class="rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700">Perlu perhatian</span></div>
        <div class="overflow-x-auto">
            <table class="min-w-[780px] w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-3 font-semibold">User</th><th class="px-5 py-3 font-semibold">Posisi / Perusahaan</th><th class="px-5 py-3 font-semibold">Sertifikat</th><th class="px-5 py-3 font-semibold">Terdaftar</th><th class="px-5 py-3 font-semibold">Aksi</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $student)
                    <tr class="transition hover:bg-rose-50/30"><td class="px-5 py-4"><div class="flex items-center gap-3"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-rose-500 to-orange-500 text-sm font-bold text-white">{{ strtoupper(substr($student->name ?: 'U', 0, 1)) }}</span><div><p class="font-semibold text-slate-800">{{ $student->name ?: 'Tanpa nama' }}</p><p class="mt-0.5 text-xs text-slate-400">{{ $student->email ?: 'Email tidak tersedia' }}</p></div></div></td><td class="px-5 py-4"><p class="text-sm text-slate-700">{{ $student->position ?: '-' }}</p><p class="mt-1 text-xs text-slate-400">{{ $student->company ?: 'Perusahaan tidak diatur' }}</p></td><td class="px-5 py-4">@if($student->certificates_count > 0)<span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700"><i class="fas fa-certificate"></i> {{ $student->certificates_count }} sertifikat</span>@else<span class="text-xs text-slate-400">Belum ada</span>@endif</td><td class="px-5 py-4 text-xs text-slate-500">{{ $student->created_at?->format('d M Y') ?? '-' }}</td><td class="px-5 py-4"><a href="{{ route('students.show', $student->id) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-100"><i class="fas fa-eye"></i> Detail</a></td></tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-16 text-center"><i class="fas fa-user-check text-4xl text-emerald-300"></i><p class="mt-4 font-semibold text-slate-700">Semua student sudah memiliki membership</p><p class="mt-1 text-sm text-slate-500">Tidak ada user yang perlu ditindaklanjuti.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-4 md:hidden">
        @forelse($students as $student)
        <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"><div class="flex items-start justify-between gap-3 p-4"><div class="flex min-w-0 items-center gap-3"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-rose-500 to-orange-500 text-sm font-bold text-white">{{ strtoupper(substr($student->name ?: 'U', 0, 1)) }}</span><div class="min-w-0"><h3 class="truncate font-semibold text-slate-800">{{ $student->name ?: 'Tanpa nama' }}</h3><p class="truncate text-xs text-slate-400">{{ $student->email }}</p></div></div><span class="shrink-0 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700">Belum bayar</span></div><div class="grid grid-cols-2 gap-3 border-t border-slate-100 p-4"><div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-400">Posisi</p><p class="mt-1 truncate text-xs font-semibold text-slate-700">{{ $student->position ?: '-' }}</p></div><div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-400">Sertifikat</p><p class="mt-1 text-xs font-semibold text-slate-700">{{ $student->certificates_count }} sertifikat</p></div></div><div class="flex items-center justify-between border-t border-slate-100 px-4 py-3"><span class="text-xs text-slate-400">Terdaftar {{ $student->created_at?->format('d M Y') ?? '-' }}</span><a href="{{ route('students.show', $student->id) }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700"><i class="fas fa-eye"></i> Detail</a></div></article>
        @empty
        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-16 text-center shadow-sm"><i class="fas fa-user-check text-4xl text-emerald-300"></i><p class="mt-4 font-semibold text-slate-700">Semua student sudah memiliki membership</p></div>
        @endforelse
    </div>

    <div>{{ $students->links() }}</div>
</div>
@endsection
