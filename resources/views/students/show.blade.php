@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 px-6 py-10">

<div class="max-w-7xl mx-auto space-y-10">

{{-- HEADER --}}
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 tracking-tight">
            Student Detail
        </h1>
        <p class="text-gray-500 mt-1 text-sm">
            Complete student performance overview
        </p>
    </div>

    <button onclick="history.back()"
        class="flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 hover:shadow-md text-gray-700 text-sm rounded-xl transition">
        <i class="fa-solid fa-arrow-left text-sm"></i>
        Kembali
    </button>
</div>


{{-- PROFILE CARD --}}
<div class="bg-white/80 backdrop-blur rounded-3xl shadow-lg p-8 border border-gray-100">
    <div class="flex items-center gap-6">

        <div class="h-24 w-24 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
            {{ strtoupper(substr($student->name,0,1)) }}
        </div>

        <div>
            <h2 class="text-2xl font-semibold text-gray-800">
                {{ $student->name }}
            </h2>
            <p class="text-gray-500 text-sm">
                {{ $student->email }}
            </p>
            <p class="text-xs text-gray-400 mt-2">
                Bergabung: {{ \Carbon\Carbon::parse($student->created_at)->format('d M Y') }}
            </p>
        </div>
    </div>
</div>


{{-- PERFORMANCE STATS --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">

    <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">Total Membership</p>
            <i class="fa-solid fa-layer-group text-indigo-500"></i>
        </div>
        <h3 class="text-3xl font-bold text-gray-800 mt-3">
            {{ $totalMembership }}
        </h3>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">Active</p>
            <i class="fa-solid fa-circle-check text-emerald-500"></i>
        </div>
        <h3 class="text-3xl font-bold text-gray-800 mt-3">
            {{ $activeMembership }}
        </h3>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">Expired</p>
            <i class="fa-solid fa-hourglass-end text-red-500"></i>
        </div>
        <h3 class="text-3xl font-bold text-gray-800 mt-3">
            {{ $expiredMembership }}
        </h3>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">Lulus Course</p>
            <i class="fa-solid fa-graduation-cap text-blue-500"></i>
        </div>
        <h3 class="text-3xl font-bold text-gray-800 mt-3">
            {{ $totalPassed }}
        </h3>
    </div>

</div>


{{-- MEMBERSHIP LIST --}}
<div class="bg-white rounded-3xl shadow-lg p-8 border border-gray-100">
    <h3 class="text-lg font-semibold text-gray-800 mb-6">
        Paket Yang Diikuti
    </h3>

    <div class="space-y-4 max-h-80 overflow-y-auto pr-2">

        @forelse($student->memberships as $membership)
            <div class="flex justify-between items-center bg-gray-50 hover:bg-gray-100 transition px-5 py-4 rounded-xl">
                <div>
                    <p class="font-medium text-gray-800">
                        {{ $membership->paket->title ?? '-' }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        Expired: {{ \Carbon\Carbon::parse($membership->expired_at)->format('d M Y') }}
                    </p>
                </div>

                @if($membership->expired_at >= now())
                    <span class="px-4 py-1 text-xs bg-emerald-100 text-emerald-600 rounded-full">
                        Active
                    </span>
                @else
                    <span class="px-4 py-1 text-xs bg-red-100 text-red-600 rounded-full">
                        Expired
                    </span>
                @endif
            </div>
        @empty
            <p class="text-sm text-gray-500">
                Belum mengikuti paket apapun.
            </p>
        @endforelse

    </div>
</div>


{{-- CERTIFICATION LIST --}}
<div class="bg-white rounded-3xl shadow-lg p-8 border border-gray-100">
    <h3 class="text-lg font-semibold text-gray-800 mb-6">
        Course Yang Sudah Lulus
    </h3>

    <div class="space-y-4 max-h-80 overflow-y-auto pr-2">

        @forelse($student->certificates as $cert)
            <div class="flex justify-between items-center bg-gray-50 hover:bg-gray-100 transition px-5 py-4 rounded-xl">
                <div>
                    <p class="font-medium text-gray-800">
                        {{ $cert->course->title ?? '-' }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        Lulus: {{ \Carbon\Carbon::parse($cert->created_at)->format('d M Y') }}
                    </p>
                </div>

                <span class="px-4 py-1 text-xs bg-blue-100 text-blue-600 rounded-full">
                    Certified
                </span>
            </div>
        @empty
            <p class="text-sm text-gray-500">
                Belum lulus course apapun.
            </p>
        @endforelse

    </div>
</div>

</div>
</div>
@endsection