@extends('layouts.app')

@section('title', request()->boolean('trashed') ? 'Courses Dihapus' : 'Courses')

@section('content')
    @php
        $assignedInstructorCount = $courses->getCollection()->filter(fn ($course) => filled($course->instructor_id))->count();
        $typeStyle = function ($title) {
            $key = strtolower(trim((string) $title));

            if (str_contains($key, 'webinar')) {
                return ['icon' => 'fa-video', 'soft' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'badge' => 'bg-emerald-100 text-emerald-800', 'border' => 'border-emerald-200', 'label' => 'Event'];
            }

            if (str_contains($key, 'konsult')) {
                return ['icon' => 'fa-comments', 'soft' => 'bg-violet-50', 'text' => 'text-violet-700', 'badge' => 'bg-violet-100 text-violet-800', 'border' => 'border-violet-200', 'label' => 'Layanan'];
            }

            if (str_contains($key, 'sertif')) {
                return ['icon' => 'fa-certificate', 'soft' => 'bg-amber-50', 'text' => 'text-amber-700', 'badge' => 'bg-amber-100 text-amber-800', 'border' => 'border-amber-200', 'label' => 'Credential'];
            }

            if (str_contains($key, 'pelatih')) {
                return ['icon' => 'fa-graduation-cap', 'soft' => 'bg-blue-50', 'text' => 'text-blue-700', 'badge' => 'bg-blue-100 text-blue-800', 'border' => 'border-blue-200', 'label' => 'Training'];
            }

            if (str_contains($key, 'tryout') || str_contains($key, 'ujian')) {
                return ['icon' => 'fa-clipboard-check', 'soft' => 'bg-rose-50', 'text' => 'text-rose-700', 'badge' => 'bg-rose-100 text-rose-800', 'border' => 'border-rose-200', 'label' => 'Assessment'];
            }

            if (str_contains($key, 'studi') || str_contains($key, 'kasus')) {
                return ['icon' => 'fa-flask', 'soft' => 'bg-cyan-50', 'text' => 'text-cyan-700', 'badge' => 'bg-cyan-100 text-cyan-800', 'border' => 'border-cyan-200', 'label' => 'Case Study'];
            }

            return ['icon' => 'fa-book-open', 'soft' => 'bg-slate-50', 'text' => 'text-slate-700', 'badge' => 'bg-slate-100 text-slate-700', 'border' => 'border-slate-200', 'label' => 'Other'];
        };
    @endphp

    <div class="mx-auto max-w-7xl space-y-6">
        <section class="overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-700 via-blue-700 to-cyan-600 p-6 text-white shadow-xl sm:p-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl"><div class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-blue-50"><i class="fas {{ $showDeleted ? 'fa-trash-can' : 'fa-book-open-reader' }}"></i> {{ $showDeleted ? 'Arsip course' : 'Katalog pembelajaran' }}</div><h1 class="text-2xl font-bold tracking-tight sm:text-3xl">{{ $showDeleted ? 'Courses Dihapus' : 'Courses' }}</h1><p class="mt-2 max-w-xl text-sm leading-6 text-blue-100 sm:text-base">{{ $showDeleted ? 'Lihat course yang memiliki deleted_at dan pulihkan kembali jika masih diperlukan.' : 'Kelola course aktif berdasarkan jenis, learning path, instructor, dan harga.' }}</p></div>
                <div class="flex flex-col gap-2 sm:flex-row"><a href="{{ route('courses.export') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/30 bg-white/10 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/20"><i class="fas fa-file-export"></i> Export Excel</a>@if($showDeleted)<a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-4 py-3 text-sm font-semibold text-blue-700 shadow-lg transition hover:bg-blue-50"><i class="fas fa-arrow-left"></i> Course aktif</a>@else<a href="{{ route('courses.trashed') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/30 bg-white/10 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/20"><i class="fas fa-trash-can"></i> Dihapus ({{ number_format($trashedCourseCount) }})</a><button type="button" onclick="openAddModal()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-4 py-3 text-sm font-semibold text-blue-700 shadow-lg transition hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-white/30"><i class="fas fa-plus"></i> Tambah Course</button>@endif</div>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex items-center justify-between"><span class="text-sm font-medium text-slate-500">Total course</span><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i class="fas fa-book"></i></span></div><p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($courses->total()) }}</p><p class="mt-1 text-xs text-slate-400">Hasil sesuai filter aktif</p></div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex items-center justify-between"><span class="text-sm font-medium text-slate-500">{{ $showDeleted ? 'Course terhapus' : 'Sudah ada instructor' }}</span><span class="flex h-10 w-10 items-center justify-center rounded-xl {{ $showDeleted ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }}"><i class="fas {{ $showDeleted ? 'fa-trash-can' : 'fa-chalkboard-user' }}"></i></span></div><p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($showDeleted ? $courses->total() : $assignedInstructorCount) }}</p><p class="mt-1 text-xs text-slate-400">{{ $showDeleted ? 'Data pada arsip ini' : 'Di halaman aktif' }}</p></div>
        </section>

        <section class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($courseTypes as $courseType)
                @php $style = $typeStyle($courseType->title); @endphp
                <a href="{{ $showDeleted ? route('courses.trashed', ['course_type_id' => $courseType->id]) : route('courses.index', ['course_type_id' => $courseType->id]) }}" class="group rounded-2xl border {{ $style['border'] }} bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex items-center justify-between gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl {{ $style['soft'] }} {{ $style['text'] }}"><i class="fas {{ $style['icon'] }}"></i></span>
                        <span class="text-2xl font-bold text-slate-900">{{ number_format($courseType->courses_count) }}</span>
                    </div>
                    <p class="mt-3 truncate text-sm font-semibold text-slate-800">{{ $courseType->title }}</p>
                    <p class="mt-1 text-xs {{ $style['text'] }}">{{ $style['label'] }} <i class="fas fa-arrow-right ml-1 text-[10px] transition group-hover:translate-x-1"></i></p>
                </a>
            @endforeach
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
            <form action="{{ $showDeleted ? route('courses.trashed') : route('courses.index') }}" method="GET" class="grid gap-3 lg:grid-cols-[190px_220px_minmax(0,1fr)_auto_auto]">
                @if($showDeleted)<input type="hidden" name="trashed" value="1">@endif
                <label class="sr-only" for="courseFilter">Filter course berdasarkan</label><select id="courseFilter" name="filter" class="rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"><option value="">Filter berdasarkan</option><option value="title" {{ request('filter') === 'title' ? 'selected' : '' }}>Judul</option><option value="learning_path" {{ request('filter') === 'learning_path' ? 'selected' : '' }}>Learning Path</option><option value="course_type" {{ request('filter') === 'course_type' ? 'selected' : '' }}>Course Type</option><option value="instructor" {{ request('filter') === 'instructor' ? 'selected' : '' }}>Instructor</option></select>
                <select name="course_type_id" class="rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"><option value="">Semua jenis course</option>@foreach($courseTypes as $courseType)<option value="{{ $courseType->id }}" {{ (string) request('course_type_id') === (string) $courseType->id ? 'selected' : '' }}>{{ $courseType->title }} ({{ $courseType->courses_count }})</option>@endforeach</select>
                <label class="relative block"><span class="sr-only">Cari course</span><i class="fas fa-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i><input type="search" name="search" value="{{ request('search') }}" placeholder="Cari judul, learning path, course type, atau instructor..." class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"></label>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700"><i class="fas fa-filter"></i> Terapkan</button>
                @if(request()->filled('search') || request()->filled('filter') || request()->filled('course_type_id'))<a href="{{ $showDeleted ? route('courses.trashed') : route('courses.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"><i class="fas fa-rotate-left"></i> Reset</a>@endif
            </form>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-2 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between"><div><h2 class="font-semibold text-slate-900">{{ $showDeleted ? 'Course yang sudah dihapus' : 'Daftar Course aktif' }}</h2><p class="mt-1 text-xs text-slate-500">Menampilkan {{ $courses->count() }} dari {{ $courses->total() }} course · Halaman {{ $courses->currentPage() }}</p></div><span class="inline-flex w-fit items-center gap-2 rounded-full {{ $showDeleted ? 'bg-rose-50 text-rose-700' : 'bg-blue-50 text-blue-700' }} px-3 py-1.5 text-xs font-semibold"><i class="fas {{ $showDeleted ? 'fa-trash-can' : 'fa-layer-group' }}"></i> {{ $courses->total() }} course</span></div>

            <div class="hidden overflow-x-auto lg:block">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-6 py-4 font-semibold">Course</th><th class="px-6 py-4 font-semibold">Learning Path</th><th class="px-6 py-4 font-semibold">Instructor</th><th class="px-6 py-4 font-semibold">Harga</th><th class="px-6 py-4 font-semibold">Status</th><th class="px-6 py-4 text-right font-semibold">Aksi</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($courses as $c)
                            @php
                                $coursePayload = ['id' => $c->id, 'learning_path_id' => $c->learning_path_id, 'course_type_id' => $c->course_type_id, 'title' => $c->title, 'thumbnail' => $c->thumbnail, 'price' => $c->price, 'description' => $c->description, 'instructor_id' => $c->instructor_id, 'objective' => $c->objective, 'is_favorit' => (bool) $c->is_favorit];
                                $courseTypeTitle = $c->courseType?->title ?? 'Course umum';
                                $courseTypeStyle = $typeStyle($courseTypeTitle);
                            @endphp
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-6 py-4"><div class="flex min-w-[310px] items-center gap-3"><div class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-xl {{ $courseTypeStyle['soft'] }} {{ $courseTypeStyle['text'] }}">@if($c->thumbnail)<img src="{{ $c->thumbnail }}" alt="{{ $c->title }}" class="h-full w-full object-cover" loading="lazy">@else<i class="fas {{ $courseTypeStyle['icon'] }}"></i>@endif</div><div class="min-w-0"><div class="mb-1 flex flex-wrap items-center gap-2"><p class="truncate font-semibold text-slate-800">{{ $c->title }}</p><span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-bold {{ $courseTypeStyle['badge'] }}"><i class="fas {{ $courseTypeStyle['icon'] }}"></i>{{ $courseTypeTitle }}</span></div><p class="mt-1 text-xs text-slate-400">Course #{{ $c->id }}</p></div></div></td>
                                <td class="max-w-[180px] truncate px-6 py-4 text-slate-600">{{ $c->learningPath->title ?? 'Belum diatur' }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-slate-600">{{ $c->instructor->name ?? 'Belum diatur' }}</td>
                                <td class="whitespace-nowrap px-6 py-4 font-semibold text-slate-700">Rp {{ number_format($c->price ?? 0, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">@if($c->is_favorit)<span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700"><i class="fas fa-star"></i> Favorit</span>@else<span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500"><i class="fas fa-minus"></i> Reguler</span>@endif</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right">@if($showDeleted)<form action="{{ route('courses.restore', $c->id) }}" method="POST" class="inline-flex">@csrf<button type="submit" class="inline-flex h-9 items-center gap-2 rounded-lg border border-emerald-200 px-3 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50" title="Pulihkan"><i class="fas fa-rotate-left"></i> Pulihkan</button></form>@else<div class="inline-flex items-center gap-2"><button type="button" data-course='@js($coursePayload)' class="edit-course inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700" title="Edit"><i class="fas fa-pen"></i></button><button type="button" data-delete-id="{{ $c->id }}" class="delete-course inline-flex h-9 w-9 items-center justify-center rounded-lg border border-rose-100 text-rose-500 transition hover:bg-rose-50 hover:text-rose-700" title="Hapus"><i class="fas fa-trash"></i></button></div>@endif</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-16 text-center"><i class="fas fa-book-open text-3xl text-slate-300"></i><p class="mt-3 font-semibold text-slate-700">Course tidak ditemukan</p><p class="mt-1 text-sm text-slate-500">Coba ubah kata kunci atau tambahkan course baru.</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="space-y-3 p-4 lg:hidden">
                @forelse($courses as $c)
                    @php
                        $coursePayload = ['id' => $c->id, 'learning_path_id' => $c->learning_path_id, 'course_type_id' => $c->course_type_id, 'title' => $c->title, 'thumbnail' => $c->thumbnail, 'price' => $c->price, 'description' => $c->description, 'instructor_id' => $c->instructor_id, 'objective' => $c->objective, 'is_favorit' => (bool) $c->is_favorit];
                        $courseTypeTitle = $c->courseType?->title ?? 'Course umum';
                        $courseTypeStyle = $typeStyle($courseTypeTitle);
                    @endphp
                    <article class="rounded-2xl border {{ $courseTypeStyle['border'] }} bg-white p-4 shadow-sm"><div class="flex items-start gap-3"><div class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-xl {{ $courseTypeStyle['soft'] }} {{ $courseTypeStyle['text'] }}">@if($c->thumbnail)<img src="{{ $c->thumbnail }}" alt="{{ $c->title }}" class="h-full w-full object-cover" loading="lazy">@else<i class="fas {{ $courseTypeStyle['icon'] }}"></i>@endif</div><div class="min-w-0 flex-1"><div class="flex flex-wrap items-center gap-2"><h3 class="truncate font-semibold text-slate-800">{{ $c->title }}</h3><span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-bold {{ $courseTypeStyle['badge'] }}"><i class="fas {{ $courseTypeStyle['icon'] }}"></i>{{ $courseTypeTitle }}</span></div><p class="mt-1 truncate text-xs text-slate-400">{{ $c->learningPath->title ?? 'Learning path belum diatur' }}</p></div>@if($c->is_favorit)<i class="fas fa-star text-amber-400" title="Favorit"></i>@endif</div><div class="mt-4 grid grid-cols-2 gap-3 rounded-xl bg-slate-50 p-3 text-sm"><div><p class="text-xs text-slate-400">Harga</p><p class="mt-1 font-semibold text-slate-700">Rp {{ number_format($c->price ?? 0, 0, ',', '.') }}</p></div><div><p class="text-xs text-slate-400">Instructor</p><p class="mt-1 truncate font-semibold text-slate-700">{{ $c->instructor->name ?? 'Belum diatur' }}</p></div></div><div class="mt-4 flex gap-2">@if($showDeleted)<form action="{{ route('courses.restore', $c->id) }}" method="POST" class="flex-1">@csrf<button type="submit" class="w-full rounded-lg border border-emerald-200 px-3 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-50"><i class="fas fa-rotate-left mr-1"></i> Pulihkan</button></form>@else<button type="button" data-course='@js($coursePayload)' class="edit-course flex-1 rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-blue-50 hover:text-blue-700"><i class="fas fa-pen mr-1"></i> Edit</button><button type="button" data-delete-id="{{ $c->id }}" class="delete-course flex-1 rounded-lg border border-rose-100 px-3 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50"><i class="fas fa-trash mr-1"></i> Hapus</button>@endif</div></article>
                @empty
                    <div class="px-4 py-12 text-center"><i class="fas fa-book-open text-3xl text-slate-300"></i><p class="mt-3 font-semibold text-slate-700">Course tidak ditemukan</p></div>
                @endforelse
            </div>
            @if($courses->hasPages())<div class="border-t border-slate-100 px-5 py-4">{{ $courses->links() }}</div>@endif
        </section>
    </div>

    <div id="courseModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="courseModalTitle"><div class="max-h-[calc(100vh-2rem)] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-2xl"><div class="sticky top-0 z-10 flex items-center justify-between border-b border-slate-100 bg-white px-5 py-4 sm:px-6"><div><p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Manajemen course</p><h2 id="courseModalTitle" class="mt-1 text-lg font-bold text-slate-900">Tambah Course</h2></div><button type="button" onclick="closeCourseModal()" class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700"><i class="fas fa-times"></i></button></div>
        <form id="courseForm" method="POST" class="space-y-4 p-5 sm:p-6">@csrf<input type="hidden" name="_method" id="courseFormMethod" value="POST"><div class="grid gap-4 sm:grid-cols-2"><div><label for="cLearningPath" class="mb-1.5 block text-sm font-semibold text-slate-700">Learning Path</label><select name="learning_path_id" id="cLearningPath" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required><option value="">Pilih learning path</option>@foreach($learningPaths as $lp)<option value="{{ $lp->id }}">{{ $lp->title }}</option>@endforeach</select></div><div><label for="cCourseType" class="mb-1.5 block text-sm font-semibold text-slate-700">Course Type</label><select name="course_type_id" id="cCourseType" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required><option value="">Pilih course type</option>@foreach($courseTypes as $ct)<option value="{{ $ct->id }}">{{ $ct->title }}</option>@endforeach</select></div></div><div><label for="cTitle" class="mb-1.5 block text-sm font-semibold text-slate-700">Judul course</label><input type="text" name="title" id="cTitle" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required></div><div class="grid gap-4 sm:grid-cols-2"><div><label for="cThumbnail" class="mb-1.5 block text-sm font-semibold text-slate-700">Thumbnail URL</label><input type="url" name="thumbnail" id="cThumbnail" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100"></div><div><label for="cPrice" class="mb-1.5 block text-sm font-semibold text-slate-700">Harga</label><input type="text" id="cPrice" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100" placeholder="Rp 0"><input type="hidden" name="price" id="cPriceHidden" value="0"></div></div><div><label for="cInstructor" class="mb-1.5 block text-sm font-semibold text-slate-700">Instructor</label><select name="instructor_id" id="cInstructor" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required><option value="">Pilih instructor</option>@foreach($instructors as $ins)<option value="{{ $ins->id }}">{{ $ins->name }}</option>@endforeach</select></div><div><label for="cDescription" class="mb-1.5 block text-sm font-semibold text-slate-700">Deskripsi</label><textarea name="description" id="cDescription" rows="3" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100"></textarea></div><div><label for="cObjective" class="mb-1.5 block text-sm font-semibold text-slate-700">Objective</label><textarea name="objective" id="cObjective" rows="3" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100"></textarea></div><div><label for="cFavorit" class="mb-1.5 block text-sm font-semibold text-slate-700">Status</label><select name="is_favorit" id="cFavorit" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100"><option value="0">Course reguler</option><option value="1">Course favorit</option></select></div><div class="flex flex-col-reverse gap-2 border-t border-slate-100 pt-4 sm:flex-row sm:justify-end"><button type="button" onclick="closeCourseModal()" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</button><button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"><i class="fas fa-save mr-1"></i> Simpan</button></div></form>
    </div></div>

    <script>
        const courseModal = document.getElementById('courseModal');
        const courseForm = document.getElementById('courseForm');
        const cPrice = document.getElementById('cPrice');
        const cPriceHidden = document.getElementById('cPriceHidden');
        function formatCoursePrice(value) { const digits = String(value ?? '').replace(/\D/g, ''); return digits ? `Rp ${new Intl.NumberFormat('id-ID').format(digits)}` : ''; }
        function openAddModal() { document.getElementById('courseModalTitle').textContent = 'Tambah Course'; courseForm.reset(); courseForm.action = @js(route('courses.store')); document.getElementById('courseFormMethod').value = 'POST'; cPrice.value = ''; cPriceHidden.value = '0'; courseModal.classList.remove('hidden'); courseModal.classList.add('flex'); document.getElementById('cTitle').focus(); }
        function openEditCourse(item) { document.getElementById('courseModalTitle').textContent = 'Edit Course'; courseForm.action = `/courses/${item.id}`; document.getElementById('courseFormMethod').value = 'PUT'; document.getElementById('cLearningPath').value = item.learning_path_id ?? ''; document.getElementById('cCourseType').value = item.course_type_id ?? ''; document.getElementById('cTitle').value = item.title ?? ''; document.getElementById('cThumbnail').value = item.thumbnail ?? ''; cPrice.value = formatCoursePrice(item.price); cPriceHidden.value = String(item.price ?? 0).replace(/\D/g, '') || '0'; document.getElementById('cInstructor').value = item.instructor_id ?? ''; document.getElementById('cDescription').value = item.description ?? ''; document.getElementById('cObjective').value = item.objective ?? ''; document.getElementById('cFavorit').value = item.is_favorit ? '1' : '0'; courseModal.classList.remove('hidden'); courseModal.classList.add('flex'); document.getElementById('cTitle').focus(); }
        function closeCourseModal() { courseModal.classList.add('hidden'); courseModal.classList.remove('flex'); }
        cPrice.addEventListener('input', () => { cPrice.value = formatCoursePrice(cPrice.value); cPriceHidden.value = cPrice.value.replace(/\D/g, '') || '0'; });
        courseForm.addEventListener('submit', () => { cPriceHidden.value = cPrice.value.replace(/\D/g, '') || '0'; });
        document.querySelectorAll('.edit-course').forEach((button) => button.addEventListener('click', () => openEditCourse(JSON.parse(button.dataset.course))));
        document.querySelectorAll('.delete-course').forEach((button) => button.addEventListener('click', () => { Swal.fire({ title: 'Hapus course?', text: 'Data yang dihapus tidak dapat dikembalikan.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonText: 'Batal', confirmButtonText: 'Ya, hapus' }).then((result) => { if (!result.isConfirmed) return; const form = document.createElement('form'); form.method = 'POST'; form.action = `/courses/${button.dataset.deleteId}`; form.innerHTML = `@csrf<input type="hidden" name="_method" value="DELETE">`; document.body.appendChild(form); form.submit(); }); }));
        courseModal.addEventListener('click', (event) => { if (event.target === courseModal) closeCourseModal(); });
        document.addEventListener('keydown', (event) => { if (event.key === 'Escape') closeCourseModal(); });
    </script>
@endsection
