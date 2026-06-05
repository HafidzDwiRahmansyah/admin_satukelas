@extends('layouts.app')

@section('content')
<div class="p-8">

<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Students Paid</h1>
        <p class="text-gray-500">Daftar student dengan membership aktif</p>
    </div>

    <a href="{{ route('students.paid.dashboard') }}"
       class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow">
        Dashboard
    </a>
</div>

<div class="bg-white rounded-2xl shadow overflow-hidden">

<table class="w-full text-sm text-left">
<thead class="bg-gray-100 text-gray-600 uppercase text-xs">
<tr>
<th class="px-6 py-4">Nama</th>
<th>Email</th>
<th>Membership</th>
<th>Sertifikasi</th>
<th class="text-right pr-6">Aksi</th>
</tr>
</thead>

<tbody class="divide-y">
@forelse($students as $student)
<tr class="hover:bg-gray-50 transition">

<td class="px-6 py-4 font-medium text-gray-800">
    {{ $student->name }}
</td>

<td class="text-gray-600">
    {{ $student->email }}
</td>

<td>
    @foreach($student->memberships as $membership)
        <span class="inline-block px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-full mr-1">
            {{ $membership->paket->title }}
        </span>
    @endforeach
</td>

<td>
    @if($student->certificates_count > 0)
        <span class="text-green-600 font-semibold">Lulus</span>
    @else
        <span class="text-red-600 font-semibold">Belum</span>
    @endif
</td>

<td class="text-right pr-6">
    <a href="{{ route('students.show',$student->id) }}"
       class="text-blue-600 hover:underline mr-3">
        Detail
    </a>

    <a href="{{ route('students.edit',$student->id) }}"
       class="text-yellow-600 hover:underline">
        Edit
    </a>
</td>

</tr>
@empty
<tr>
<td colspan="5" class="text-center py-10 text-gray-500">
    Tidak ada data student.
</td>
</tr>
@endforelse
</tbody>
</table>

</div>

<div class="mt-6">
    {{ $students->links() }}
</div>

</div>
@endsection