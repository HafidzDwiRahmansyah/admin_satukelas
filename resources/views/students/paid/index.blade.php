@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="mb-2 flex items-center gap-2 text-sm text-slate-500">
                <i class="fas fa-users text-blue-600"></i><span>Manajemen User</span><i class="fas fa-chevron-right text-xs text-slate-400"></i><span>Sudah Bayar</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">User Sudah Bayar</h1>
            <p class="mt-1 text-sm text-slate-500">Daftar student yang memiliki riwayat membership.</p>
        </div>
        <a href="{{ route('students.unpaid') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700">
            <i class="fas fa-user-clock"></i> Lihat Belum Bayar
        </a>
    </div>

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5"><div class="flex items-center justify-between"><p class="text-xs font-medium text-slate-500 sm:text-sm">Total Sudah Bayar</p><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600"><i class="fas fa-users"></i></span></div><p class="mt-3 text-2xl font-bold text-slate-900 sm:text-3xl">{{ number_format($paidStudentCount) }}</p></div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5"><div class="flex items-center justify-between"><p class="text-xs font-medium text-slate-500 sm:text-sm">Membership Aktif</p><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600"><i class="fas fa-circle-check"></i></span></div><p class="mt-3 text-2xl font-bold text-slate-900 sm:text-3xl">{{ number_format($activeStudentCount) }}</p></div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5"><div class="flex items-center justify-between"><p class="text-xs font-medium text-slate-500 sm:text-sm">Membership Expired</p><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 text-amber-600"><i class="fas fa-clock"></i></span></div><p class="mt-3 text-2xl font-bold text-slate-900 sm:text-3xl">{{ number_format($expiredStudentCount) }}</p></div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5"><div class="flex items-center justify-between"><p class="text-xs font-medium text-slate-500 sm:text-sm">Halaman Ini</p><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-50 text-violet-600"><i class="fas fa-list"></i></span></div><p class="mt-3 text-2xl font-bold text-slate-900 sm:text-3xl">{{ $students->count() }}</p><p class="mt-1 text-xs text-slate-400">dari {{ $students->total() }} hasil</p></div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        <form action="{{ route('students.paid') }}" method="GET" class="flex flex-col gap-3 sm:flex-row">
            <div class="relative flex-1"><i class="fas fa-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i><input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau perusahaan..." class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"></div>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700"><i class="fas fa-search"></i> Cari</button>
            @if(request()->filled('search'))<a href="{{ route('students.paid') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"><i class="fas fa-rotate-left"></i> Reset</a>@endif
        </form>
    </div>

    <div class="hidden overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm md:block">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4"><div><h2 class="font-semibold text-slate-900">Daftar User Sudah Bayar</h2><p class="mt-1 text-xs text-slate-500">Menampilkan {{ $students->count() }} dari {{ $students->total() }} user</p></div><span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Membership tersedia</span></div>
        <div class="overflow-x-auto">
            <table class="min-w-[900px] w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-3 font-semibold">User</th><th class="px-5 py-3 font-semibold">Posisi / Perusahaan</th><th class="px-5 py-3 font-semibold">Membership</th><th class="px-5 py-3 font-semibold">Sertifikat</th><th class="px-5 py-3 font-semibold">Aksi</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $student)
                    @php $latestMemberships = $student->memberships->sortByDesc('expired_at')->take(2); $hasActiveMembership = $student->memberships->contains(fn($membership) => $membership->expired_at && $membership->expired_at->gte(now())); @endphp
                    <tr class="transition hover:bg-blue-50/40">
                        <td class="px-5 py-4"><div class="flex items-center gap-3"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-violet-500 text-sm font-bold text-white">{{ strtoupper(substr($student->name ?: 'U', 0, 1)) }}</span><div><p class="font-semibold text-slate-800">{{ $student->name ?: 'Tanpa nama' }}</p><p class="mt-0.5 text-xs text-slate-400">{{ $student->email ?: 'Email tidak tersedia' }}</p></div></div></td>
                        <td class="px-5 py-4"><p class="text-sm text-slate-700">{{ $student->position ?: '-' }}</p><p class="mt-1 text-xs text-slate-400">{{ $student->company ?: 'Perusahaan tidak diatur' }}</p></td>
                        <td class="px-5 py-4"><div class="space-y-1.5">@foreach($latestMemberships as $membership)<div class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full {{ $membership->expired_at && $membership->expired_at->gte(now()) ? 'bg-emerald-500' : 'bg-amber-500' }}"></span><span class="max-w-[190px] truncate text-xs font-medium text-slate-700">{{ $membership->paket->title ?? 'Paket membership' }}</span></div><p class="pl-3.5 text-[11px] text-slate-400">{{ $membership->expired_at ? 'Sampai ' . $membership->expired_at->format('d M Y') : 'Tanggal berakhir tidak tersedia' }}</p>@endforeach</div></td>
                        <td class="px-5 py-4">@if($student->certificates_count > 0)<span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700"><i class="fas fa-check"></i> {{ $student->certificates_count }} sertifikat</span>@else<span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-500">Belum ada</span>@endif</td>
                        <td class="px-5 py-4"><div class="flex items-center gap-2"><a href="{{ route('students.show', $student->id) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-100"><i class="fas fa-eye"></i> Detail</a>@if($hasActiveMembership)<span class="rounded-full bg-emerald-50 px-2 py-1 text-[11px] font-semibold text-emerald-700">Aktif</span>@else<span class="rounded-full bg-amber-50 px-2 py-1 text-[11px] font-semibold text-amber-700">Expired</span>@endif</div></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-16 text-center"><i class="fas fa-users-slash text-4xl text-slate-300"></i><p class="mt-4 font-semibold text-slate-700">User tidak ditemukan</p><p class="mt-1 text-sm text-slate-500">Coba ubah kata kunci pencarian.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-4 md:hidden">
        @forelse($students as $student)
        @php $latestMembership = $student->memberships->sortByDesc('expired_at')->first(); $hasActiveMembership = $student->memberships->contains(fn($membership) => $membership->expired_at && $membership->expired_at->gte(now())); @endphp
        <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"><div class="flex items-start justify-between gap-3 border-b border-slate-100 p-4"><div class="flex min-w-0 items-center gap-3"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-violet-500 text-sm font-bold text-white">{{ strtoupper(substr($student->name ?: 'U', 0, 1)) }}</span><div class="min-w-0"><h3 class="truncate font-semibold text-slate-800">{{ $student->name ?: 'Tanpa nama' }}</h3><p class="truncate text-xs text-slate-400">{{ $student->email }}</p></div></div><span class="shrink-0 rounded-full {{ $hasActiveMembership ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }} px-2.5 py-1 text-xs font-semibold">{{ $hasActiveMembership ? 'Aktif' : 'Expired' }}</span></div><div class="grid grid-cols-2 gap-3 p-4"><div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-400">Membership</p><p class="mt-1 truncate text-xs font-semibold text-slate-700">{{ $latestMembership?->paket?->title ?? 'Paket membership' }}</p><p class="mt-1 text-[11px] text-slate-400">{{ $latestMembership?->expired_at ? 'Sampai ' . $latestMembership->expired_at->format('d M Y') : '-' }}</p></div><div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-400">Sertifikat</p><p class="mt-1 text-xs font-semibold text-slate-700">{{ $student->certificates_count }} sertifikat</p></div></div><div class="flex items-center justify-end border-t border-slate-100 px-4 py-3"><a href="{{ route('students.show', $student->id) }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700"><i class="fas fa-eye"></i> Lihat detail</a></div></article>
        @empty
        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-16 text-center shadow-sm"><i class="fas fa-users-slash text-4xl text-slate-300"></i><p class="mt-4 font-semibold text-slate-700">User tidak ditemukan</p></div>
        @endforelse
    </div>

    <div>{{ $students->links() }}</div>
</div>
@endsection
