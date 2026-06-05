@extends('layouts.app')

@section('title','Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-bold text-blue-700">Selamat Datang di Dashboard</h1>
    <div class="text-sm text-gray-500">Halo, Admin</div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Card 1 -->
    <div class="relative z-0 bg-white rounded-xl p-6 shadow hover:shadow-lg border border-blue-100 transition">
      <div class="flex items-center gap-4">
        <div class="p-3 bg-blue-100 rounded-lg">
          <i class="fas fa-users text-blue-600 text-xl"></i>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-blue-700">Manajemen User</h3>
          <p class="text-sm text-gray-500">Kelola data pengguna.</p>
        </div>
      </div>
    </div>

    <!-- Card 2 -->
    <div class="relative z-0 bg-white rounded-xl p-6 shadow hover:shadow-lg border border-blue-100 transition">
      <div class="flex items-center gap-4">
        <div class="p-3 bg-blue-100 rounded-lg">
          <i class="fas fa-user-shield text-blue-600 text-xl"></i>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-blue-700">Manajemen Admin</h3>
          <p class="text-sm text-gray-500">Atur akses dan data admin.</p>
        </div>
      </div>
    </div>

    <!-- Card 3 -->
    <div class="relative z-0 bg-white rounded-xl p-6 shadow hover:shadow-lg border border-blue-100 transition">
      <div class="flex items-center gap-4">
        <div class="p-3 bg-blue-100 rounded-lg">
          <i class="fas fa-book-open text-blue-600 text-xl"></i>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-blue-700">Manajemen Pelatihan</h3>
          <p class="text-sm text-gray-500">Learning Path, Course, Lessons, Webinar.</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
