@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="mb-2 flex items-center gap-2 text-sm text-slate-500">
                <i class="fas fa-book-open text-blue-600"></i>
                <span>Manajemen Pelatihan</span>
                <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                <span>Webinar</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Manajemen Webinar</h1>
            <p class="mt-1 max-w-2xl text-sm text-slate-500">
                Kelola jadwal webinar, status publikasi, template certificate, dan pengguna bersertifikat.
            </p>
        </div>

        <button type="button" onclick="openFormModal()"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100">
            <i class="fas fa-plus"></i>
            <span>Tambah Webinar</span>
        </button>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Webinar</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($totalWebinars) }}</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i class="fas fa-video"></i></span>
            </div>
            <p class="mt-3 text-xs text-slate-400">Sesuai filter yang dipilih</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Sudah Published</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($publishedWebinars) }}</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600"><i class="fas fa-circle-check"></i></span>
            </div>
            <p class="mt-3 text-xs text-slate-400">Webinar yang dapat dilihat peserta</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Template Certificate</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($webinarsWithTemplates) }}</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 text-violet-600"><i class="fas fa-certificate"></i></span>
            </div>
            <p class="mt-3 text-xs text-slate-400">Webinar yang memiliki template</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Pencetak Sertifikat Webinar</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($webinarsWithCertificates) }}</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600"><i class="fas fa-user-check"></i></span>
            </div>
            <p class="mt-3 text-xs text-slate-400">Total user unik yang sudah mencetak</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        <form action="{{ route('webinars.index') }}" method="GET" class="grid grid-cols-1 gap-3 lg:grid-cols-[180px_minmax(0,1fr)_auto_auto]">
            <label class="sr-only" for="filter">Filter berdasarkan</label>
            <select id="filter" name="filter" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                <option value="">Semua data</option>
                <option value="course" {{ request('filter') === 'course' ? 'selected' : '' }}>Course</option>
                <option value="published" {{ request('filter') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="meeting_link" {{ request('filter') === 'meeting_link' ? 'selected' : '' }}>Meeting Link</option>
            </select>

            <label class="sr-only" for="search">Cari webinar</label>
            <div class="relative">
                <i class="fas fa-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input id="search" type="text" name="search" value="{{ request('search') }}" placeholder="Cari course, status yes/no, atau meeting link..." class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
            </div>

            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700 focus:outline-none focus:ring-4 focus:ring-slate-200">
                <i class="fas fa-filter"></i> Filter
            </button>

            @if(request()->filled('filter') || request()->filled('search'))
            <a href="{{ route('webinars.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                <i class="fas fa-rotate-left"></i> Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Desktop table --}}
    <div class="hidden overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm md:block">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h2 class="font-semibold text-slate-900">Daftar Webinar</h2>
                <p class="mt-1 text-xs text-slate-500">Menampilkan {{ $webinars->count() }} dari {{ $webinars->total() }} data</p>
            </div>
            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">Halaman {{ $webinars->currentPage() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-[1080px] w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Course</th>
                        <th class="px-5 py-3 font-semibold">Certificate</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 font-semibold">Jadwal</th>
                        <th class="px-5 py-3 font-semibold">Kapasitas</th>
                        <th class="px-5 py-3 font-semibold">Meeting</th>
                        <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($webinars as $webinar)
                    @php
                        $templateUrl = $webinar->course?->learningPath?->certificate_url;
                        $certificateCount = collect($webinar->course?->certificates)
                            ->where('type', 'Partisipasi')
                            ->unique('user_id')
                            ->count();
                    @endphp
                    <tr class="transition hover:bg-blue-50/40">
                        <td class="px-5 py-4">
                            <div class="flex min-w-[210px] items-center gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i class="fas fa-video"></i></span>
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $webinar->course->title ?? 'Course tidak tersedia' }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">ID Webinar #{{ $webinar->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            @if($templateUrl)
                            <a href="{{ $templateUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-full bg-violet-50 px-2.5 py-1 text-xs font-semibold text-violet-700 transition hover:bg-violet-100"><i class="fas fa-check"></i> Ada</a>
                            @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-500"><i class="fas fa-minus"></i> Tidak ada</span>
                            @endif
                            <p class="mt-1 text-xs text-slate-400">Template</p>
                        </td>
                        <td class="px-5 py-4">
                            @if($certificateCount > 0)
                            <a href="{{ route('certificate.index', ['search' => $webinar->course_id, 'type' => 'Partisipasi']) }}" class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100"><i class="fas fa-user-check"></i> {{ $certificateCount }} pengguna</a>
                            @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-500"><i class="fas fa-user-slash"></i> Belum ada</span>
                            @endif
                            <p class="mt-1 text-xs text-slate-400">User unik, tipe webinar</p>
                        </td>
                        <td class="px-5 py-4">
                            @if($webinar->is_published)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Published</span>
                            @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700"><span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Draft</span>
                            @endif
                            <p class="mt-2 text-xs text-slate-500">{{ $webinar->time_starts?->format('d M Y, H:i') ?? '-' }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-semibold text-slate-800">{{ number_format($webinar->total_seats) }} seats</p>
                            <p class="mt-1 text-xs text-slate-400">sampai {{ $webinar->time_ends?->format('H:i') ?? '-' }}</p>
                        </td>
                        <td class="px-5 py-4">
                            @if($webinar->meeting_link)
                            <a href="{{ $webinar->meeting_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline"><i class="fas fa-arrow-up-right-from-square"></i> Buka link</a>
                            @else
                            <span class="text-xs text-slate-400">Belum tersedia</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="openFormModal({{ $webinar->id }}, '{{ $webinar->course_id }}', '{{ $webinar->total_seats }}', '{{ $webinar->time_starts?->format('Y-m-d\\TH:i') }}', '{{ $webinar->time_ends?->format('Y-m-d\\TH:i') }}', '{{ $webinar->meeting_link }}', '{{ $webinar->passcode }}', {{ $webinar->is_published ? 1 : 0 }}, '{{ addslashes($webinar->course->title ?? '') }}')" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-amber-200 bg-amber-50 text-amber-600 transition hover:bg-amber-100" title="Edit webinar"><i class="fas fa-pen text-xs"></i></button>
                                <form method="POST" action="{{ route('webinars.destroy', $webinar->id) }}" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 transition hover:bg-red-100 delete-btn" title="Hapus webinar"><i class="fas fa-trash text-xs"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-16 text-center"><i class="fas fa-video-slash text-4xl text-slate-300"></i><p class="mt-4 font-semibold text-slate-700">Webinar belum ditemukan</p><p class="mt-1 text-sm text-slate-500">Coba ubah filter atau tambahkan webinar baru.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile cards --}}
    <div class="space-y-4 md:hidden">
        <div class="flex items-center justify-between">
            <div><h2 class="font-semibold text-slate-900">Daftar Webinar</h2><p class="mt-1 text-xs text-slate-500">{{ $webinars->total() }} webinar ditemukan</p></div>
            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">Halaman {{ $webinars->currentPage() }}</span>
        </div>

        @forelse($webinars as $webinar)
        @php
            $templateUrl = $webinar->course?->learningPath?->certificate_url;
            $certificateCount = collect($webinar->course?->certificates)
                ->where('type', 'Partisipasi')
                ->unique('user_id')
                ->count();
        @endphp
        <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-start justify-between gap-3 border-b border-slate-100 p-4">
                <div class="flex min-w-0 items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i class="fas fa-video"></i></span>
                    <div class="min-w-0"><h3 class="truncate font-semibold text-slate-900">{{ $webinar->course->title ?? 'Course tidak tersedia' }}</h3><p class="mt-0.5 text-xs text-slate-400">Webinar #{{ $webinar->id }}</p></div>
                </div>
                @if($webinar->is_published)<span class="shrink-0 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">Published</span>@else<span class="shrink-0 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">Draft</span>@endif
            </div>

            <div class="grid grid-cols-2 gap-3 p-4 text-sm">
                <div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-400">Template</p>@if($templateUrl)<a href="{{ $templateUrl }}" target="_blank" rel="noopener noreferrer" class="mt-1 inline-flex items-center gap-1 text-xs font-semibold text-violet-700"><i class="fas fa-check"></i> Ada</a>@else<p class="mt-1 text-xs font-medium text-slate-500">Tidak ada</p>@endif</div>
                <div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-400">Pencetak</p>@if($certificateCount > 0)<a href="{{ route('certificate.index', ['search' => $webinar->course_id, 'type' => 'Partisipasi']) }}" class="mt-1 inline-flex items-center gap-1 text-xs font-semibold text-emerald-700"><i class="fas fa-user-check"></i> {{ $certificateCount }} pengguna</a>@else<p class="mt-1 text-xs font-medium text-slate-500">Belum ada</p>@endif</div>
                <div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-400">Mulai</p><p class="mt-1 text-xs font-semibold text-slate-700">{{ $webinar->time_starts?->format('d M Y, H:i') ?? '-' }}</p></div>
                <div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-400">Kapasitas</p><p class="mt-1 text-xs font-semibold text-slate-700">{{ number_format($webinar->total_seats) }} seats</p></div>
            </div>

            <div class="flex items-center justify-between border-t border-slate-100 px-4 py-3">
                @if($webinar->meeting_link)<a href="{{ $webinar->meeting_link }}" target="_blank" rel="noopener noreferrer" class="text-xs font-semibold text-blue-600"><i class="fas fa-link mr-1"></i> Meeting link</a>@else<span class="text-xs text-slate-400">Meeting link belum tersedia</span>@endif
                <div class="flex gap-2">
                    <button type="button" onclick="openFormModal({{ $webinar->id }}, '{{ $webinar->course_id }}', '{{ $webinar->total_seats }}', '{{ $webinar->time_starts?->format('Y-m-d\\TH:i') }}', '{{ $webinar->time_ends?->format('Y-m-d\\TH:i') }}', '{{ $webinar->meeting_link }}', '{{ $webinar->passcode }}', {{ $webinar->is_published ? 1 : 0 }}, '{{ addslashes($webinar->course->title ?? '') }}')" class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600" title="Edit webinar"><i class="fas fa-pen text-xs"></i></button>
                    <form method="POST" action="{{ route('webinars.destroy', $webinar->id) }}" class="delete-form">@csrf @method('DELETE')<button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-600 delete-btn" title="Hapus webinar"><i class="fas fa-trash text-xs"></i></button></form>
                </div>
            </div>
        </article>
        @empty
        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-16 text-center shadow-sm"><i class="fas fa-video-slash text-4xl text-slate-300"></i><p class="mt-4 font-semibold text-slate-700">Webinar belum ditemukan</p><p class="mt-1 text-sm text-slate-500">Coba ubah filter atau tambahkan webinar baru.</p></div>
        @endforelse
    </div>

    <div class="pt-1">{{ $webinars->links() }}</div>
</div>

{{-- Modal Form Create/Edit --}}
<div id="formModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm">
    <div class="max-h-[95vh] w-full max-w-xl overflow-y-auto rounded-2xl bg-white shadow-2xl">
        <div class="flex items-start justify-between border-b border-slate-100 px-5 py-4 sm:px-6">
            <div><p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Manajemen Webinar</p><h2 id="formTitle" class="mt-1 text-xl font-bold text-slate-900">Tambah Webinar</h2></div>
            <button type="button" onclick="closeFormModal()" class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup modal"><i class="fas fa-xmark"></i></button>
        </div>

        <form id="webinarForm" method="POST" action="{{ route('webinars.store') }}" class="space-y-4 p-5 sm:p-6">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="id" id="webinarId">
            <div><label for="courseId" class="mb-1.5 block text-sm font-semibold text-slate-700">Course</label><select name="course_id" id="courseId" class="w-full" required></select></div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div><label for="totalSeats" class="mb-1.5 block text-sm font-semibold text-slate-700">Total Seats</label><input type="number" name="total_seats" id="totalSeats" min="1" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"></div>
                <div><label for="isPublished" class="mb-1.5 block text-sm font-semibold text-slate-700">Status</label><select name="is_published" id="isPublished" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"><option value="0">Draft</option><option value="1">Published</option></select></div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div><label for="timeStarts" class="mb-1.5 block text-sm font-semibold text-slate-700">Mulai</label><input type="datetime-local" name="time_starts" id="timeStarts" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"></div>
                <div><label for="timeEnds" class="mb-1.5 block text-sm font-semibold text-slate-700">Selesai</label><input type="datetime-local" name="time_ends" id="timeEnds" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"></div>
            </div>

            <div><label for="meetingLink" class="mb-1.5 block text-sm font-semibold text-slate-700">Meeting Link <span class="font-normal text-slate-400">(opsional)</span></label><input type="url" name="meeting_link" id="meetingLink" placeholder="https://..." class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"></div>
            <div><label for="passcode" class="mb-1.5 block text-sm font-semibold text-slate-700">Passcode <span class="font-normal text-slate-400">(opsional)</span></label><input type="text" name="passcode" id="passcode" placeholder="Masukkan passcode meeting" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"></div>

            <div class="flex flex-col-reverse gap-2 border-t border-slate-100 pt-4 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeFormModal()" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Batal</button>
                <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100">Simpan Webinar</button>
            </div>
        </form>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<style>
    .select2-container--default .select2-selection--single { height: 43px; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 6px 10px; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 29px; color: #334155; font-size: 0.875rem; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 41px; right: 8px; }
</style>

<script>
    $(document).ready(function() {
        $('.delete-btn').click(function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus webinar ini?',
                text: 'Data webinar akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => { if (result.isConfirmed) form.submit(); });
        });
    });

    function initializeCourseSelect() {
        if ($('#courseId').hasClass('select2-hidden-accessible')) return;

        $('#courseId').select2({
            placeholder: 'Cari dan pilih course...',
            allowClear: true,
            dropdownParent: $('#formModal'),
            ajax: {
                url: '{{ route("courses.search") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) { return { q: params.term }; },
                processResults: function(data) { return { results: data }; },
                cache: true
            },
            minimumInputLength: 1,
            width: '100%'
        });
    }

    function openFormModal(id = null, course_id = '', total_seats = '', time_starts = '', time_ends = '', meeting_link = '', passcode = '', is_published = 0, course_title = '') {
        initializeCourseSelect();
        $('#formModal').removeClass('hidden').addClass('flex');
        $('#webinarForm')[0].reset();
        $('#courseId').val(null).trigger('change');

        if (id) {
            $('#formTitle').text('Edit Webinar');
            $('#webinarForm').attr('action', '/webinars/' + id);
            $('#formMethod').val('PUT');
            $('#webinarId').val(id);
            $('#totalSeats').val(total_seats);
            $('#timeStarts').val(time_starts);
            $('#timeEnds').val(time_ends);
            $('#meetingLink').val(meeting_link);
            $('#passcode').val(passcode);
            $('#isPublished').val(is_published);
            if (course_id) $('#courseId').append(new Option(course_title, course_id, true, true)).trigger('change');
        } else {
            $('#formTitle').text('Tambah Webinar');
            $('#webinarForm').attr('action', '{{ route("webinars.store") }}');
            $('#formMethod').val('POST');
        }
    }

    function closeFormModal() { $('#formModal').addClass('hidden').removeClass('flex'); }

    document.getElementById('formModal').addEventListener('click', function(event) {
        if (event.target === this) closeFormModal();
    });
</script>
@endsection
