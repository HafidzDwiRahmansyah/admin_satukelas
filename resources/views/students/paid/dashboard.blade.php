@extends('layouts.app')

@section('content')
<div class="px-6 py-8 space-y-10">

{{-- HERO --}}
<div class="rounded-3xl bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-700 p-10 text-white shadow-xl">

<h1 class="text-3xl font-bold">Students Analytics Dashboard</h1>

<p class="text-indigo-100 mt-2 text-sm">
Monitor membership performance and certification progress.
</p>

<div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-6">

<div>
<p class="text-indigo-200 text-sm">Total Students</p>
<p class="text-2xl font-semibold">{{ $totalPaid }}</p>
</div>

<div>
<p class="text-indigo-200 text-sm">Certified</p>
<p class="text-2xl font-semibold">{{ $totalPassed }}</p>
</div>

<div>
<p class="text-indigo-200 text-sm">Active Membership</p>
<p class="text-2xl font-semibold">{{ $totalActive }}</p>
</div>

<div>
<p class="text-indigo-200 text-sm">Completion Rate</p>
<p class="text-2xl font-semibold">
{{ $totalPaid > 0 ? round(($totalPassed/$totalPaid)*100) : 0 }}%
</p>
</div>

</div>

</div>


{{-- QUICK STATS --}}
<div class="grid md:grid-cols-4 gap-6">

<div class="bg-white p-6 rounded-2xl shadow">
<p class="text-gray-500 text-sm">Total Paid</p>
<h2 class="text-3xl font-bold">{{ $totalPaid }}</h2>
</div>

<div class="bg-emerald-50 p-6 rounded-2xl shadow">
<p class="text-emerald-600 text-sm">Active</p>
<h2 class="text-3xl font-bold text-emerald-700">{{ $totalActive }}</h2>
</div>

<div class="bg-gray-100 p-6 rounded-2xl shadow">
<p class="text-gray-600 text-sm">Expired</p>
<h2 class="text-3xl font-bold text-gray-700">{{ $totalExpired }}</h2>
</div>

<div onclick="openNotPassed()"
class="bg-rose-50 p-6 rounded-2xl shadow cursor-pointer">

<p class="text-rose-600 text-sm">Belum Lulus</p>
<h2 class="text-3xl font-bold text-rose-700">
{{ $totalNotPassed }}
</h2>

</div>

</div>


{{-- TABLE --}}
<div class="bg-white rounded-3xl shadow p-8">

<h2 class="text-lg font-semibold mb-6">
Paket Membership Performance
</h2>

<div class="overflow-x-auto">

<table class="w-full text-sm">

<thead class="text-gray-500 border-b">
<tr>
<th class="text-left pb-4">Paket</th>
<th class="text-center pb-4">Total</th>
<th class="text-center pb-4">Active</th>
<th class="text-center pb-4">Expired</th>
<th class="text-right pb-4">Action</th>
</tr>
</thead>

<tbody>

@foreach($paketSummary as $paket)

<tr class="border-b hover:bg-gray-50">

<td class="py-4 font-medium">{{ $paket->title }}</td>

<td class="text-center">{{ $paket->total_members }}</td>

<td class="text-center text-emerald-600 font-semibold">
{{ $paket->active_members }}
</td>

<td class="text-center text-rose-600 font-semibold">
{{ $paket->expired_members }}
</td>

<td class="text-right">
<a href="{{ route('students.paid',['paket'=>$paket->id]) }}"
class="px-4 py-2 text-xs bg-indigo-600 text-white rounded-lg">
Detail
</a>
</td>

</tr>

@endforeach

</tbody>
</table>

</div>
</div>


{{-- SWEET ALERT --}}
<script>
function openNotPassed(){

Swal.fire({
title:'Detail Belum Lulus',
width:600,
html:`
<div style="max-height:400px;overflow:auto;padding:10px;text-align:left">

@foreach($courseNotPassedStats as $stat)

<div style="display:flex;justify-content:space-between;
padding:12px 0;border-bottom:1px solid #eee">

<span>
{{ $stat->course->title ?? '-' }}
<br>
<small style="color:#6b7280">
Peserta: {{ $stat->participant }} |
Lulus: {{ $stat->passed }}
</small>
</span>

<strong style="color:#dc2626">
{{ $stat->not_passed }}
</strong>

</div>

@endforeach

</div>
`,
confirmButtonText:'Tutup'
});

}
</script>

</div>
@endsection