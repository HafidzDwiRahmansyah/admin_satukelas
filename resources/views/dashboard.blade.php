@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $authUser = auth()->user();
    $maxPathCourses = max(1, (int) $topLearningPaths->max('courses_count'));
@endphp

<div class="mx-auto max-w-7xl space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-medium text-blue-600">Overview Platform</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Selamat datang, {{ $authUser?->name ?? 'Admin' }}</h1>
            <p class="mt-1 text-sm text-slate-500">Pantau aktivitas dan perkembangan Satukelas dari satu tempat.</p>
        </div>
        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i class="fas fa-calendar-day"></i></span>
            <div>
                <p class="text-xs text-slate-400">Hari ini</p>
                <p class="text-sm font-semibold text-slate-700">{{ now()->translatedFormat('d F Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Primary metrics --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <a href="{{ route('superadmin.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
            <div class="flex items-start justify-between">
                <div><p class="text-sm font-medium text-slate-500">Total User</p><p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($totalUsers) }}</p></div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-600 group-hover:text-white"><i class="fas fa-users"></i></span>
            </div>
            <p class="mt-3 text-xs text-slate-500"><span class="font-semibold text-blue-600">{{ number_format($totalStudents) }}</span> student terdaftar</p>
        </a>

        <a href="{{ route('learning_paths.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-violet-200 hover:shadow-md">
            <div class="flex items-start justify-between">
                <div><p class="text-sm font-medium text-slate-500">Learning Path</p><p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($totalLearningPaths) }}</p></div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 text-violet-600 transition group-hover:bg-violet-600 group-hover:text-white"><i class="fas fa-layer-group"></i></span>
            </div>
            <p class="mt-3 text-xs text-slate-500"><span class="font-semibold text-violet-600">{{ number_format($totalCourses) }}</span> course tersedia</p>
        </a>

        <a href="{{ route('webinars.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">
            <div class="flex items-start justify-between">
                <div><p class="text-sm font-medium text-slate-500">Webinar</p><p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($totalWebinars) }}</p></div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition group-hover:bg-emerald-600 group-hover:text-white"><i class="fas fa-video"></i></span>
            </div>
            <p class="mt-3 text-xs text-slate-500"><span class="font-semibold text-emerald-600">{{ number_format($publishedWebinars) }}</span> sudah published</p>
        </a>

        <a href="{{ route('certificate.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-amber-200 hover:shadow-md">
            <div class="flex items-start justify-between">
                <div><p class="text-sm font-medium text-slate-500">Certificate</p><p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($totalCertificates) }}</p></div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition group-hover:bg-amber-600 group-hover:text-white"><i class="fas fa-certificate"></i></span>
            </div>
            <p class="mt-3 text-xs text-slate-500"><span class="font-semibold text-amber-600">{{ number_format($webinarCertificateUsers) }}</span> user webinar bersertifikat</p>
        </a>
    </div>

    {{-- Content overview --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-2">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 sm:px-6">
                <div><h2 class="font-semibold text-slate-900">Learning Path</h2><p class="mt-1 text-xs text-slate-500">Learning path dengan jumlah course terbanyak</p></div>
                <a href="{{ route('learning_paths.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Lihat semua <i class="fas fa-arrow-right ml-1 text-xs"></i></a>
            </div>
            <div class="space-y-5 p-5 sm:p-6">
                @forelse($topLearningPaths as $path)
                @php $pathPercentage = min(100, round(($path->courses_count / $maxPathCourses) * 100)); @endphp
                <div>
                    <div class="mb-2 flex items-center justify-between gap-3">
                        <div class="min-w-0"><p class="truncate text-sm font-semibold text-slate-800">{{ $path->title }}</p><p class="mt-0.5 text-xs text-slate-400">{{ $path->certificate_url ? 'Template certificate tersedia' : 'Belum ada template certificate' }}</p></div>
                        <span class="shrink-0 text-sm font-bold text-slate-700">{{ $path->courses_count }} course</span>
                    </div>
                    <div class="h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-gradient-to-r from-blue-500 to-violet-500" style="width: {{ $pathPercentage }}%"></div></div>
                </div>
                @empty
                <div class="py-8 text-center text-sm text-slate-500">Belum ada data learning path.</div>
                @endforelse
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4 sm:px-6"><h2 class="font-semibold text-slate-900">Komposisi User</h2><p class="mt-1 text-xs text-slate-500">Distribusi role pengguna</p></div>
            <div class="space-y-4 p-5 sm:p-6">
                <div class="grid grid-cols-3 gap-2">
                    <div class="rounded-xl bg-blue-50 p-3"><p class="text-xs text-blue-600">Student</p><p class="mt-1 text-lg font-bold text-blue-900">{{ number_format($totalStudents) }}</p></div>
                    <div class="rounded-xl bg-violet-50 p-3"><p class="text-xs text-violet-600">Admin</p><p class="mt-1 text-lg font-bold text-violet-900">{{ number_format($totalAdmins) }}</p></div>
                    <div class="rounded-xl bg-emerald-50 p-3"><p class="text-xs text-emerald-600">Instructor</p><p class="mt-1 text-lg font-bold text-emerald-900">{{ number_format($totalInstructors) }}</p></div>
                </div>
                @foreach($roleBreakdown->take(5) as $role)
                @php $rolePercentage = $totalUsers > 0 ? round(($role->total / $totalUsers) * 100, 1) : 0; @endphp
                <div>
                    <div class="mb-1.5 flex justify-between text-xs"><span class="font-medium capitalize text-slate-600">{{ $role->role ?: 'Tidak diatur' }}</span><span class="text-slate-400">{{ number_format($role->total) }} ({{ $rolePercentage }}%)</span></div>
                    <div class="h-2 rounded-full bg-slate-100"><div class="h-2 rounded-full bg-blue-500" style="width: {{ min(100, $rolePercentage) }}%"></div></div>
                </div>
                @endforeach
            </div>
        </section>
    </div>

    {{-- Recent data --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-5">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-3">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 sm:px-6"><div><h2 class="font-semibold text-slate-900">Webinar Terbaru</h2><p class="mt-1 text-xs text-slate-500">Data webinar yang baru ditambahkan</p></div><a href="{{ route('webinars.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Lihat semua</a></div>
            <div class="overflow-x-auto">
                <table class="min-w-[620px] w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-3 font-semibold">Course</th><th class="px-5 py-3 font-semibold">Jadwal</th><th class="px-5 py-3 font-semibold">Status</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentWebinars as $webinar)
                        <tr class="transition hover:bg-blue-50/40"><td class="px-5 py-3.5"><p class="font-semibold text-slate-800">{{ $webinar->course->title ?? 'Course tidak tersedia' }}</p><p class="mt-0.5 text-xs text-slate-400">{{ number_format($webinar->total_seats) }} seats</p></td><td class="px-5 py-3.5 text-xs text-slate-600">{{ $webinar->time_starts?->format('d M Y, H:i') ?? '-' }}</td><td class="px-5 py-3.5">@if($webinar->is_published)<span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">Published</span>@else<span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">Draft</span>@endif</td></tr>
                        @empty
                        <tr><td colspan="3" class="px-5 py-10 text-center text-sm text-slate-500">Belum ada webinar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-2">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 sm:px-6"><div><h2 class="font-semibold text-slate-900">User Terbaru</h2><p class="mt-1 text-xs text-slate-500">Pengguna yang baru terdaftar</p></div><a href="{{ route('students.paid') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Lihat</a></div>
            <div class="divide-y divide-slate-100">
                @forelse($recentUsers as $user)
                <div class="flex items-center gap-3 px-5 py-3.5 sm:px-6"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-violet-500 text-xs font-bold text-white">{{ strtoupper(substr($user->name ?: 'U', 0, 1)) }}</span><div class="min-w-0 flex-1"><p class="truncate text-sm font-semibold text-slate-800">{{ $user->name ?: 'Tanpa nama' }}</p><p class="truncate text-xs text-slate-400">{{ $user->email }}</p></div><span class="shrink-0 rounded-full bg-slate-100 px-2 py-1 text-[10px] font-semibold uppercase text-slate-500">{{ $user->role }}</span></div>
                @empty
                <p class="px-5 py-10 text-center text-sm text-slate-500">Belum ada user.</p>
                @endforelse
            </div>
        </section>
    </div>

    {{-- Quick access and activity --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="font-semibold text-slate-900">Akses Cepat</h2>
            <p class="mt-1 text-xs text-slate-500">Menu yang paling sering digunakan</p>
            <div class="mt-5 grid grid-cols-2 gap-3">
                <a href="{{ route('courses.index') }}" class="rounded-xl border border-slate-200 p-3 transition hover:border-blue-300 hover:bg-blue-50"><i class="fas fa-graduation-cap text-blue-600"></i><p class="mt-2 text-xs font-semibold text-slate-700">Course</p></a>
                <a href="{{ route('learning_paths.index') }}" class="rounded-xl border border-slate-200 p-3 transition hover:border-violet-300 hover:bg-violet-50"><i class="fas fa-layer-group text-violet-600"></i><p class="mt-2 text-xs font-semibold text-slate-700">Learning Path</p></a>
                <a href="{{ route('webinars.index') }}" class="rounded-xl border border-slate-200 p-3 transition hover:border-emerald-300 hover:bg-emerald-50"><i class="fas fa-video text-emerald-600"></i><p class="mt-2 text-xs font-semibold text-slate-700">Webinar</p></a>
                <a href="{{ route('certificate.index') }}" class="rounded-xl border border-slate-200 p-3 transition hover:border-amber-300 hover:bg-amber-50"><i class="fas fa-certificate text-amber-600"></i><p class="mt-2 text-xs font-semibold text-slate-700">Certificate</p></a>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 sm:px-6"><div><h2 class="font-semibold text-slate-900">Aktivitas Terbaru</h2><p class="mt-1 text-xs text-slate-500">{{ number_format($activeUsers) }} user tercatat di log aktivitas</p></div><a href="{{ url('/active-users') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Lihat semua</a></div>
            <div class="divide-y divide-slate-100">
                @forelse($recentActivities as $activity)
                <div class="flex items-center gap-3 px-5 py-3.5 sm:px-6"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500"><i class="fas fa-clock text-sm"></i></span><div class="min-w-0 flex-1"><p class="truncate text-sm font-medium text-slate-700">{{ $activity->user->name ?? 'User tidak tersedia' }}</p><p class="truncate text-xs text-slate-400">{{ $activity->user->email ?? 'Aktivitas pengguna' }}</p></div><span class="shrink-0 text-xs text-slate-400">{{ $activity->last_activity ? \Carbon\Carbon::parse($activity->last_activity)->diffForHumans() : '-' }}</span></div>
                @empty
                <p class="px-5 py-10 text-center text-sm text-slate-500">Belum ada aktivitas user.</p>
                @endforelse
            </div>
        </section>
    </div>

    <div class="flex flex-wrap gap-3 text-xs text-slate-500">
        <span><i class="fas fa-box-archive mr-1 text-slate-400"></i>{{ number_format($totalAssets) }} asset</span>
        <span><i class="fas fa-id-card mr-1 text-slate-400"></i>{{ number_format($totalMemberships) }} membership</span>
        <span><i class="fas fa-user-tie mr-1 text-slate-400"></i>{{ number_format($totalInstructors) }} instructor</span>
    </div>
</div>
@endsection
