@extends('layouts.app')

@section('title', 'Audit Data')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">
    <section class="overflow-hidden rounded-3xl bg-gradient-to-br from-rose-700 via-orange-600 to-amber-500 p-6 text-white shadow-xl sm:p-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-orange-50">
                    <i class="fas fa-shield-halved"></i> Data quality monitor
                </div>
                <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">Audit kekurangan data</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-orange-50 sm:text-base">Temukan course tanpa template certificate, materi yang belum lengkap, webinar yang belum terhubung, dan user yang belum memiliki certificate dari satu halaman.</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row">
                <a href="{{ route('flow.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/25 bg-white/10 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/20"><i class="fas fa-diagram-project"></i> Lihat alur</a>
                <a href="{{ route('assets.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-4 py-3 text-sm font-semibold text-orange-700 shadow-lg transition hover:bg-orange-50"><i class="fas fa-certificate"></i> Kelola template</a>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <button type="button" onclick="openAuditModal('certificate')" class="w-full cursor-pointer rounded-2xl border border-rose-200 bg-rose-50 p-5 text-left shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-rose-100"><div class="flex items-center justify-between"><span class="text-sm font-semibold text-rose-700">Certificate perlu review</span><i class="fas fa-certificate text-rose-600"></i></div><p class="mt-3 text-3xl font-bold text-rose-950">{{ number_format($metrics['coursesWithoutTemplates'] + $metrics['incompleteTemplates'] + $metrics['completedWithoutCertificateCases']) }}</p><p class="mt-1 text-xs text-rose-700">Template kosong, konfigurasi rusak, atau hasil 100% belum tercetak</p><span class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-rose-700">Klik untuk melihat detail <i class="fas fa-arrow-right text-[10px]"></i></span></button>
        <button type="button" onclick="openAuditModal('structure')" class="w-full cursor-pointer rounded-2xl border border-amber-200 bg-amber-50 p-5 text-left shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-amber-100"><div class="flex items-center justify-between"><span class="text-sm font-semibold text-amber-700">Struktur perlu diisi</span><i class="fas fa-sitemap text-amber-600"></i></div><p class="mt-3 text-3xl font-bold text-amber-950">{{ number_format($metrics['coursesWithoutTopics'] + $metrics['topicsWithoutContent'] + $metrics['coursesWithoutLessons']) }}</p><p class="mt-1 text-xs text-amber-700">Course, topic, atau lesson masih kosong</p><span class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-amber-700">Klik untuk melihat detail <i class="fas fa-arrow-right text-[10px]"></i></span></button>
        <button type="button" onclick="openAuditModal('completion')" class="w-full cursor-pointer rounded-2xl border border-blue-200 bg-blue-50 p-5 text-left shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-blue-100"><div class="flex items-center justify-between"><span class="text-sm font-semibold text-blue-700">Selesai 100% tanpa certificate</span><i class="fas fa-user-clock text-blue-600"></i></div><p class="mt-3 text-3xl font-bold text-blue-950">{{ number_format($metrics['completedWithoutCertificateCases']) }}</p><p class="mt-1 text-xs text-blue-700">{{ number_format($metrics['completedWithoutCertificateUsers']) }} user unik, hanya course yang memiliki lesson</p><span class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-blue-700">Klik untuk melihat detail <i class="fas fa-arrow-right text-[10px]"></i></span></button>
        <button type="button" onclick="openAuditModal('operations')" class="w-full cursor-pointer rounded-2xl border border-violet-200 bg-violet-50 p-5 text-left shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-violet-100"><div class="flex items-center justify-between"><span class="text-sm font-semibold text-violet-700">Operasional perlu review</span><i class="fas fa-triangle-exclamation text-violet-600"></i></div><p class="mt-3 text-3xl font-bold text-violet-950">{{ number_format($metrics['unlinkedWebinars'] + $metrics['lessonsWithoutMaterial'] + $metrics['coursesWithoutInstructor']) }}</p><p class="mt-1 text-xs text-violet-700">Webinar, lesson, atau instructor belum lengkap</p><span class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-violet-700">Klik untuk melihat detail <i class="fas fa-arrow-right text-[10px]"></i></span></button>
    </section>

    <div class="rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800 shadow-sm sm:px-5"><i class="fas fa-circle-info mr-2"></i><strong>Catatan:</strong> Course tanpa template tetap perlu ditinjau pada tabel <code class="rounded bg-white/70 px-1.5 py-0.5 text-xs">certificate_template</code>. Kasus user yang dianggap masalah hanya course yang memiliki lesson, bukan Webinar atau Konsultasi, enrollment-nya sudah <strong>100% selesai</strong>, tetapi belum memiliki baris certificate untuk user dan course tersebut.</div>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"><div><h2 class="font-semibold text-slate-900">1. Kesiapan certificate</h2><p class="mt-1 text-sm text-slate-500">Pisahkan template yang belum tersedia, konfigurasi yang belum lengkap, dan user yang belum tercetak.</p></div><a href="{{ route('certificate.index') }}" class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50"><i class="fas fa-print"></i> Lihat hasil cetak</a></div>
        <div class="mt-5 grid grid-cols-1 gap-5 xl:grid-cols-3">
            <div class="rounded-2xl border border-rose-100 bg-rose-50/50 p-4">
                <div class="flex items-center justify-between gap-3"><div><h3 class="font-semibold text-rose-950">Course tanpa template</h3><p class="mt-1 text-xs text-rose-700">{{ number_format($metrics['coursesWithoutTemplates']) }} course</p></div><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-100 text-rose-600"><i class="fas fa-file-circle-xmark"></i></span></div>
                <div class="mt-4 space-y-2">
                    @forelse($courseWithoutTemplates as $course)
                        <a href="{{ route('courses.index', ['search' => $course->title, 'filter' => 'title']) }}" class="flex items-center gap-2 rounded-lg bg-white px-3 py-2 transition hover:bg-rose-100"><span class="min-w-0 flex-1 truncate text-xs font-semibold text-slate-700">{{ $course->title }}</span><span class="shrink-0 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] text-slate-500">{{ $course->courseType->title ?? 'Umum' }}</span></a>
                    @empty
                        <p class="rounded-lg bg-white px-3 py-4 text-center text-xs text-emerald-700"><i class="fas fa-check mr-1"></i> Semua course sudah memiliki template</p>
                    @endforelse
                </div>
                @if($metrics['coursesWithoutTemplates'] > 8)<p class="mt-3 text-[11px] text-rose-700">Menampilkan 8 data terbaru dari {{ number_format($metrics['coursesWithoutTemplates']) }}.</p>@endif
            </div>

            <div class="rounded-2xl border border-amber-100 bg-amber-50/50 p-4">
                <div class="flex items-center justify-between gap-3"><div><h3 class="font-semibold text-amber-950">Template belum lengkap</h3><p class="mt-1 text-xs text-amber-700">{{ number_format($metrics['incompleteTemplates']) }} konfigurasi</p></div><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-100 text-amber-600"><i class="fas fa-triangle-exclamation"></i></span></div>
                <div class="mt-4 space-y-2">
                    @forelse($incompleteTemplates as $template)
                        @php $templateIssue = !$template->course_id || !$template->course?->title ? 'Course kosong' : (!$template->asset_id || !$template->asset?->file_name ? 'Asset kosong' : 'Perlu dicek'); @endphp
                        <a href="{{ route('assets.index') }}" class="flex items-center gap-2 rounded-lg bg-white px-3 py-2 transition hover:bg-amber-100"><span class="min-w-0 flex-1 truncate text-xs font-semibold text-slate-700">{{ $template->course?->title ?? 'Nama course belum ada' }}</span><span class="shrink-0 rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold text-amber-800">{{ $templateIssue }}</span></a>
                    @empty
                        <p class="rounded-lg bg-white px-3 py-4 text-center text-xs text-emerald-700"><i class="fas fa-check mr-1"></i> Semua template sudah lengkap</p>
                    @endforelse
                </div>
                @if($metrics['incompleteTemplates'] > 8)<p class="mt-3 text-[11px] text-amber-700">Menampilkan 8 konfigurasi terbaru dari {{ number_format($metrics['incompleteTemplates']) }}.</p>@endif
            </div>

            <div class="rounded-2xl border border-blue-100 bg-blue-50/50 p-4">
                <div class="flex items-center justify-between gap-3"><div><h3 class="font-semibold text-blue-950">100% selesai, belum certificate</h3><p class="mt-1 text-xs text-blue-700">{{ number_format($metrics['completedWithoutCertificateCases']) }} kasus user-course</p></div><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-blue-600"><i class="fas fa-user-clock"></i></span></div>
                <div class="mt-4 space-y-2">
                    @forelse($completedWithoutCertificateCases as $case)
                        <a href="{{ route('students.show', $case->user_id) }}" class="flex items-center gap-2 rounded-lg bg-white px-3 py-2 transition hover:bg-blue-100"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-blue-100 text-[10px] font-bold text-blue-700">{{ strtoupper(substr($case->user_name ?: 'U', 0, 1)) }}</span><span class="min-w-0 flex-1"><span class="block truncate text-xs font-semibold text-slate-700">{{ $case->user_name ?: 'Tanpa nama' }}</span><span class="block truncate text-[10px] text-slate-500">{{ $case->course_title }}</span></span><span class="shrink-0 rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-semibold text-blue-800">100%</span><span class="shrink-0 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">{{ $case->template_status }}</span></a>
                    @empty
                        <p class="rounded-lg bg-white px-3 py-4 text-center text-xs text-emerald-700"><i class="fas fa-check mr-1"></i> Tidak ada kasus completed tanpa certificate</p>
                    @endforelse
                </div>
                @if($metrics['completedWithoutCertificateCases'] > 12)<p class="mt-3 text-[11px] text-blue-700">Menampilkan 12 kasus terbaru dari {{ number_format($metrics['completedWithoutCertificateCases']) }}.</p>@endif
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-start gap-3"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700"><i class="fas fa-layer-group"></i></span><div><h2 class="font-semibold text-slate-900">2. Struktur pembelajaran</h2><p class="mt-1 text-sm text-slate-500">Webinar tidak ikut dihitung pada pemeriksaan topic dan lesson.</p></div></div>
            <div class="mt-5 space-y-5">
                <div><div class="mb-2 flex items-center justify-between gap-3"><p class="text-sm font-semibold text-slate-700">Course tanpa topic</p><span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-800">{{ number_format($metrics['coursesWithoutTopics']) }}</span></div>@forelse($courseWithoutTopics as $course)<a href="{{ route('topics.index', ['course_id' => $course->id]) }}" class="mb-2 flex items-center gap-2 rounded-lg border border-slate-100 px-3 py-2 text-xs hover:bg-amber-50"><span class="min-w-0 flex-1 truncate font-semibold text-slate-700">{{ $course->title }}</span><i class="fas fa-arrow-right text-slate-400"></i></a>@empty<p class="rounded-lg bg-emerald-50 px-3 py-3 text-xs text-emerald-700"><i class="fas fa-check mr-1"></i> Tidak ada course yang kosong.</p>@endforelse</div>
                <div><div class="mb-2 flex items-center justify-between gap-3"><p class="text-sm font-semibold text-slate-700">Topic tanpa lesson dan quiz</p><span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-800">{{ number_format($metrics['topicsWithoutContent']) }}</span></div>@forelse($topicWithoutContent as $topic)<a href="{{ route('topics.index', ['search' => $topic->title]) }}" class="mb-2 flex items-center gap-2 rounded-lg border border-slate-100 px-3 py-2 text-xs hover:bg-amber-50"><span class="min-w-0 flex-1 truncate font-semibold text-slate-700">{{ $topic->course?->title ?? 'Course tidak tersedia' }} / {{ $topic->title }}</span><i class="fas fa-arrow-right text-slate-400"></i></a>@empty<p class="rounded-lg bg-emerald-50 px-3 py-3 text-xs text-emerald-700"><i class="fas fa-check mr-1"></i> Semua topic memiliki lesson atau quiz.</p>@endforelse</div>
                <div><div class="mb-2 flex items-center justify-between gap-3"><p class="text-sm font-semibold text-slate-700">Course tanpa lesson</p><span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-800">{{ number_format($metrics['coursesWithoutLessons']) }}</span></div><p class="mb-2 text-[11px] leading-5 text-slate-500">Webinar dan Konsultasi dikecualikan karena termasuk event atau layanan.</p>@forelse($courseWithoutLessons as $course)<a href="{{ route('courses.index', ['search' => $course->title, 'filter' => 'title']) }}" class="mb-2 flex items-center gap-2 rounded-lg border border-slate-100 px-3 py-2 text-xs hover:bg-amber-50"><span class="min-w-0 flex-1 truncate font-semibold text-slate-700">{{ $course->title }}</span><span class="shrink-0 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] text-slate-500">{{ $course->courseType->title ?? 'Umum' }}</span></a>@empty<p class="rounded-lg bg-emerald-50 px-3 py-3 text-xs text-emerald-700"><i class="fas fa-check mr-1"></i> Tidak ada course regular tanpa lesson.</p>@endforelse</div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-start gap-3"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-700"><i class="fas fa-toolbox"></i></span><div><h2 class="font-semibold text-slate-900">3. Operasional</h2><p class="mt-1 text-sm text-slate-500">Data pendukung yang dapat membuat flow tidak berjalan sempurna.</p></div></div>
            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
                <a href="{{ route('lessons.index') }}" class="rounded-xl border border-slate-200 p-3 transition hover:border-violet-300 hover:bg-violet-50"><p class="text-xs text-slate-500">Lesson tanpa material URL</p><p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($metrics['lessonsWithoutMaterial']) }}</p><p class="mt-1 text-[11px] text-violet-700">Perlu link atau file</p></a>
                <a href="{{ route('courses.index') }}" class="rounded-xl border border-slate-200 p-3 transition hover:border-violet-300 hover:bg-violet-50"><p class="text-xs text-slate-500">Course tanpa instructor</p><p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($metrics['coursesWithoutInstructor']) }}</p><p class="mt-1 text-[11px] text-violet-700">Perlu penanggung jawab</p></a>
                <a href="{{ route('webinars.index') }}" class="rounded-xl border border-slate-200 p-3 transition hover:border-violet-300 hover:bg-violet-50"><p class="text-xs text-slate-500">Webinar tanpa course</p><p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($metrics['unlinkedWebinars']) }}</p><p class="mt-1 text-[11px] text-violet-700">{{ number_format($metrics['draftWebinars']) }} masih draft</p></a>
            </div>
            <div class="mt-5"><div class="mb-2 flex items-center justify-between"><h3 class="text-sm font-semibold text-slate-700">Webinar belum terhubung</h3><span class="text-xs text-slate-400">{{ number_format($metrics['unlinkedWebinars']) }} data</span></div>@forelse($unlinkedWebinars as $webinar)<a href="{{ route('webinars.index') }}" class="mb-2 flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 text-xs hover:bg-violet-50"><span class="font-semibold text-slate-700">Webinar #{{ $webinar->id }}</span><span class="text-slate-400">{{ $webinar->time_starts?->format('d M Y, H:i') ?? 'Jadwal belum ada' }}</span></a>@empty<p class="rounded-lg bg-emerald-50 px-3 py-3 text-xs text-emerald-700"><i class="fas fa-check mr-1"></i> Semua webinar sudah terhubung ke course.</p>@endforelse</div>
        </section>
    </div>
</div>

<div id="auditModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="auditModalTitle" onclick="if (event.target === this) closeAuditModal()">
    <div class="flex max-h-[calc(100vh-2rem)] w-full max-w-4xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 sm:px-6">
            <div class="flex min-w-0 items-center gap-3"><span id="auditModalIcon" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600"><i class="fas fa-list-check"></i></span><div class="min-w-0"><h2 id="auditModalTitle" class="truncate text-lg font-bold text-slate-900">Detail audit</h2><p id="auditModalSummary" class="mt-0.5 truncate text-xs text-slate-500"></p></div></div>
            <button type="button" onclick="closeAuditModal()" class="ml-3 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup detail audit"><i class="fas fa-xmark"></i></button>
        </div>
        <div id="auditModalBody" class="min-h-0 overflow-y-auto p-5 sm:p-6"></div>
        <div class="flex justify-end border-t border-slate-100 px-5 py-3 sm:px-6"><button type="button" onclick="closeAuditModal()" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">Tutup</button></div>
    </div>
</div>

<template id="audit-template-certificate">
    <div class="space-y-5">
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3"><div class="rounded-xl bg-rose-50 p-3"><p class="text-xs text-rose-700">Course tanpa template</p><p class="mt-1 text-2xl font-bold text-rose-950">{{ number_format($metrics['coursesWithoutTemplates']) }}</p></div><div class="rounded-xl bg-amber-50 p-3"><p class="text-xs text-amber-700">Template belum lengkap</p><p class="mt-1 text-2xl font-bold text-amber-950">{{ number_format($metrics['incompleteTemplates']) }}</p></div><div class="rounded-xl bg-blue-50 p-3"><p class="text-xs text-blue-700">100% tanpa certificate</p><p class="mt-1 text-2xl font-bold text-blue-950">{{ number_format($metrics['completedWithoutCertificateCases']) }}</p></div></div>
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
            <div><div class="mb-2 flex items-center justify-between"><h3 class="text-sm font-semibold text-slate-800">Course tanpa template</h3><a href="{{ route('courses.index') }}" class="text-xs font-semibold text-rose-600">Buka course</a></div><div class="space-y-2">@forelse($courseWithoutTemplates as $course)<a href="{{ route('courses.index', ['search' => $course->title, 'filter' => 'title']) }}" class="flex items-center gap-2 rounded-lg border border-slate-100 px-3 py-2 text-xs hover:bg-rose-50"><span class="min-w-0 flex-1 truncate font-semibold text-slate-700">{{ $course->title }}</span><span class="shrink-0 text-[10px] text-slate-500">{{ $course->courseType->title ?? 'Umum' }}</span></a>@empty<p class="rounded-lg bg-emerald-50 px-3 py-3 text-xs text-emerald-700">Tidak ada.</p>@endforelse</div></div>
            <div><div class="mb-2 flex items-center justify-between"><h3 class="text-sm font-semibold text-slate-800">Template belum lengkap</h3><a href="{{ route('assets.index') }}" class="text-xs font-semibold text-amber-600">Buka asset</a></div><div class="space-y-2">@forelse($incompleteTemplates as $template)<a href="{{ route('assets.index') }}" class="flex items-center gap-2 rounded-lg border border-slate-100 px-3 py-2 text-xs hover:bg-amber-50"><span class="min-w-0 flex-1 truncate font-semibold text-slate-700">{{ $template->course?->title ?? 'Course kosong' }}</span><span class="shrink-0 text-[10px] text-amber-700">{{ !$template->asset_id || !$template->asset?->file_name ? 'Asset kosong' : 'Course kosong' }}</span></a>@empty<p class="rounded-lg bg-emerald-50 px-3 py-3 text-xs text-emerald-700">Tidak ada.</p>@endforelse</div></div>
            <div><div class="mb-2 flex items-center justify-between"><h3 class="text-sm font-semibold text-slate-800">Selesai 100% tanpa certificate</h3><a href="{{ route('certificate.index') }}" class="text-xs font-semibold text-blue-600">Buka certificate</a></div><div class="space-y-2">@forelse($completedWithoutCertificateCases as $case)<a href="{{ route('students.show', $case->user_id) }}" class="flex items-center gap-2 rounded-lg border border-slate-100 px-3 py-2 text-xs hover:bg-blue-50"><span class="min-w-0 flex-1"><span class="block truncate font-semibold text-slate-700">{{ $case->user_name ?: 'Tanpa nama' }}</span><span class="block truncate text-[10px] text-slate-500">{{ $case->course_title }}</span></span><span class="shrink-0 rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-semibold text-blue-800">100%</span></a>@empty<p class="rounded-lg bg-emerald-50 px-3 py-3 text-xs text-emerald-700">Tidak ada.</p>@endforelse</div></div>
        </div>
    </div>
</template>

<template id="audit-template-structure">
    <div class="space-y-5">
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3"><div class="rounded-xl bg-amber-50 p-3"><p class="text-xs text-amber-700">Course tanpa topic</p><p class="mt-1 text-2xl font-bold text-amber-950">{{ number_format($metrics['coursesWithoutTopics']) }}</p></div><div class="rounded-xl bg-orange-50 p-3"><p class="text-xs text-orange-700">Topic tanpa isi</p><p class="mt-1 text-2xl font-bold text-orange-950">{{ number_format($metrics['topicsWithoutContent']) }}</p></div><div class="rounded-xl bg-slate-100 p-3"><p class="text-xs text-slate-600">Course tanpa lesson</p><p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($metrics['coursesWithoutLessons']) }}</p></div></div>
        <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-xs leading-5 text-blue-800"><i class="fas fa-circle-info mr-1"></i> Webinar dan Konsultasi tidak dimasukkan sebagai kekurangan lesson karena merupakan event atau layanan.</div>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-3"><div><h3 class="mb-2 text-sm font-semibold text-slate-800">Course tanpa topic</h3><div class="space-y-2">@forelse($courseWithoutTopics as $course)<a href="{{ route('topics.index', ['course_id' => $course->id]) }}" class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 text-xs hover:bg-amber-50"><span class="truncate font-semibold text-slate-700">{{ $course->title }}</span><i class="fas fa-arrow-right text-slate-400"></i></a>@empty<p class="text-xs text-emerald-700">Tidak ada.</p>@endforelse</div></div><div><h3 class="mb-2 text-sm font-semibold text-slate-800">Topic tanpa lesson atau quiz</h3><div class="space-y-2">@forelse($topicWithoutContent as $topic)<a href="{{ route('topics.index', ['search' => $topic->title]) }}" class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 text-xs hover:bg-amber-50"><span class="truncate font-semibold text-slate-700">{{ $topic->title }}</span><i class="fas fa-arrow-right text-slate-400"></i></a>@empty<p class="text-xs text-emerald-700">Tidak ada.</p>@endforelse</div></div><div><h3 class="mb-2 text-sm font-semibold text-slate-800">Course tanpa lesson</h3><div class="space-y-2">@forelse($courseWithoutLessons as $course)<a href="{{ route('courses.index', ['search' => $course->title, 'filter' => 'title']) }}" class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 text-xs hover:bg-amber-50"><span class="truncate font-semibold text-slate-700">{{ $course->title }}</span><span class="text-[10px] text-slate-500">{{ $course->courseType->title ?? 'Umum' }}</span></a>@empty<p class="text-xs text-emerald-700">Tidak ada.</p>@endforelse</div></div></div>
    </div>
</template>

<template id="audit-template-completion">
    <div class="space-y-5"><div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm leading-6 text-blue-800"><i class="fas fa-circle-info mr-1"></i> Daftar ini hanya berisi enrollment student dengan status <strong>100% selesai</strong>, course memiliki lesson, bukan Webinar/Konsultasi, dan belum memiliki certificate untuk course tersebut.</div><div class="flex items-center justify-between"><h3 class="text-sm font-semibold text-slate-800">Daftar kasus user-course</h3><a href="{{ route('certificate.index') }}" class="text-xs font-semibold text-blue-600">Buka certificate</a></div><div class="overflow-x-auto rounded-xl border border-slate-100"><table class="min-w-full text-left text-sm"><thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-4 py-3">User</th><th class="px-4 py-3">Course</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Aksi</th></tr></thead><tbody class="divide-y divide-slate-100">@forelse($completedWithoutCertificateCases as $case)<tr><td class="px-4 py-3"><p class="font-semibold text-slate-700">{{ $case->user_name ?: 'Tanpa nama' }}</p><p class="text-xs text-slate-400">{{ $case->user_email }}</p></td><td class="px-4 py-3"><p class="max-w-[260px] truncate text-slate-700">{{ $case->course_title }}</p><p class="text-xs text-slate-400">{{ $case->course_type_title ?? 'Umum' }}</p></td><td class="px-4 py-3"><span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-800">100% selesai</span></td><td class="px-4 py-3"><a href="{{ route('students.show', $case->user_id) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">Lihat user</a></td></tr>@empty<tr><td colspan="4" class="px-4 py-8 text-center text-sm text-emerald-700">Tidak ada kasus.</td></tr>@endforelse</tbody></table></div>@if($metrics['completedWithoutCertificateCases'] > 12)<p class="text-xs text-slate-500">Menampilkan 12 kasus terbaru dari {{ number_format($metrics['completedWithoutCertificateCases']) }} kasus.</p>@endif</div>
</template>

<template id="audit-template-operations">
    <div class="space-y-5"><div class="grid grid-cols-1 gap-3 sm:grid-cols-3"><a href="{{ route('lessons.index') }}" class="rounded-xl border border-slate-200 p-4 hover:border-violet-300 hover:bg-violet-50"><p class="text-xs text-slate-500">Lesson tanpa material URL</p><p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($metrics['lessonsWithoutMaterial']) }}</p><p class="mt-1 text-xs text-violet-700">Buka Lessons</p></a><a href="{{ route('courses.index') }}" class="rounded-xl border border-slate-200 p-4 hover:border-violet-300 hover:bg-violet-50"><p class="text-xs text-slate-500">Course tanpa instructor</p><p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($metrics['coursesWithoutInstructor']) }}</p><p class="mt-1 text-xs text-violet-700">Buka Courses</p></a><a href="{{ route('webinars.index') }}" class="rounded-xl border border-slate-200 p-4 hover:border-violet-300 hover:bg-violet-50"><p class="text-xs text-slate-500">Webinar tanpa course</p><p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($metrics['unlinkedWebinars']) }}</p><p class="mt-1 text-xs text-violet-700">Buka Webinars</p></a></div><div><div class="mb-2 flex items-center justify-between"><h3 class="text-sm font-semibold text-slate-800">Contoh webinar belum terhubung</h3><span class="text-xs text-slate-400">{{ number_format($metrics['unlinkedWebinars']) }} data</span></div><div class="space-y-2">@forelse($unlinkedWebinars as $webinar)<a href="{{ route('webinars.index') }}" class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 text-xs hover:bg-violet-50"><span class="font-semibold text-slate-700">Webinar #{{ $webinar->id }}</span><span class="text-slate-400">{{ $webinar->time_starts?->format('d M Y, H:i') ?? 'Jadwal belum ada' }}</span></a>@empty<p class="rounded-lg bg-emerald-50 px-3 py-3 text-xs text-emerald-700">Semua webinar sudah terhubung.</p>@endforelse</div></div></div>
</template>

<script>
    const auditModal = document.getElementById('auditModal');
    const auditModalBody = document.getElementById('auditModalBody');
    const auditModalTitle = document.getElementById('auditModalTitle');
    const auditModalSummary = document.getElementById('auditModalSummary');
    const auditModalIcon = document.getElementById('auditModalIcon');
    const auditDetailUrl = @js(route('data-audit.details', ['section' => '__SECTION__']));
    const auditModalConfig = {
        certificate: { title: 'Detail certificate perlu review', summary: 'Seluruh template dan kasus certificate yang perlu ditindaklanjuti', icon: 'fa-certificate', color: 'bg-rose-100 text-rose-700' },
        structure: { title: 'Detail struktur perlu diisi', summary: 'Seluruh course, topic, dan lesson yang belum lengkap', icon: 'fa-sitemap', color: 'bg-amber-100 text-amber-700' },
        completion: { title: 'Detail selesai 100% tanpa certificate', summary: 'Seluruh enrollment valid yang memenuhi kriteria audit', icon: 'fa-user-clock', color: 'bg-blue-100 text-blue-700' },
        operations: { title: 'Detail operasional perlu review', summary: 'Seluruh material, instructor, dan webinar yang belum lengkap', icon: 'fa-triangle-exclamation', color: 'bg-violet-100 text-violet-700' }
    };

    function escapeAuditHtml(value) {
        return String(value ?? '').replace(/[&<>'"]/g, (character) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' }[character]));
    }

    function renderAuditGroups(section, groups) {
        return `<div class="space-y-5">${groups.map((group) => {
            const items = group.items.length
                ? group.items.map((item) => `<a href="${escapeAuditHtml(item.url)}" class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 transition hover:border-blue-300 hover:bg-blue-50"><span class="min-w-0 flex-1"><span class="block truncate text-sm font-semibold text-slate-700">${escapeAuditHtml(item.title)}</span><span class="mt-0.5 block truncate text-xs text-slate-400">${escapeAuditHtml(item.meta)}</span></span>${item.secondary ? `<span class="shrink-0 rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-semibold text-slate-600">${escapeAuditHtml(item.secondary)}</span>` : ''}<span class="shrink-0 rounded-full bg-blue-100 px-2.5 py-1 text-[10px] font-semibold text-blue-800">${escapeAuditHtml(item.status)}</span><i class="fas fa-arrow-up-right-from-square text-xs text-slate-400"></i></a>`).join('')
                : '<div class="rounded-xl bg-emerald-50 px-4 py-4 text-sm text-emerald-700"><i class="fas fa-check mr-1"></i> Tidak ada data yang perlu diperbaiki.</div>';
            const pagination = group.last_page > 1 ? `<div class="mt-3 flex items-center justify-between gap-3"><span class="text-xs text-slate-500">Halaman ${group.page} dari ${group.last_page} - ${Number(group.total).toLocaleString('id-ID')} data</span><div class="flex gap-2"><button type="button" ${group.page <= 1 ? 'disabled' : ''} onclick="openAuditModal('${section}', ${group.page - 1})" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50"><i class="fas fa-chevron-left"></i></button><button type="button" ${group.page >= group.last_page ? 'disabled' : ''} onclick="openAuditModal('${section}', ${group.page + 1})" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50"><i class="fas fa-chevron-right"></i></button></div></div>` : `<p class="mt-3 text-xs text-slate-500">${Number(group.total).toLocaleString('id-ID')} data ditampilkan.</p>`;
            return `<section><div class="mb-3 flex items-center justify-between gap-3"><h3 class="font-semibold text-slate-900">${escapeAuditHtml(group.title)}</h3><span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700">${Number(group.total).toLocaleString('id-ID')}</span></div><div class="space-y-2">${items}</div>${pagination}</section>`;
        }).join('')}</div>`;
    }

    function openAuditModal(key, page = 1) {
        const config = auditModalConfig[key];
        if (!config) return;
        auditModalTitle.textContent = config.title;
        auditModalSummary.textContent = config.summary;
        auditModalIcon.className = `flex h-10 w-10 shrink-0 items-center justify-center rounded-xl ${config.color}`;
        auditModalIcon.innerHTML = `<i class="fas ${config.icon}"></i>`;
        auditModalBody.innerHTML = '<div class="flex items-center justify-center gap-3 py-16 text-sm text-slate-500"><i class="fas fa-spinner fa-spin text-blue-600"></i> Memuat seluruh detail data...</div>';
        auditModal.classList.remove('hidden');
        auditModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        const url = `${auditDetailUrl.replace('__SECTION__', key)}?page=${page}&per_page=100`;
        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then((response) => { if (!response.ok) throw new Error('Gagal mengambil detail audit'); return response.json(); })
            .then((payload) => { auditModalBody.innerHTML = renderAuditGroups(key, payload.groups || []); })
            .catch(() => { auditModalBody.innerHTML = '<div class="rounded-xl bg-rose-50 px-4 py-4 text-sm text-rose-700"><i class="fas fa-circle-exclamation mr-1"></i> Detail audit gagal dimuat. Silakan coba lagi.</div>'; });
    }

    function closeAuditModal() {
        auditModal.classList.add('hidden');
        auditModal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !auditModal.classList.contains('hidden')) closeAuditModal();
    });
</script>
@endsection
