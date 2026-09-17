@extends('layouts.app')

@section('title', 'Alur Sistem')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">
    <section class="overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 p-6 text-white shadow-xl sm:p-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-blue-100">
                    <i class="fas fa-diagram-project"></i> Peta alur admin
                </div>
                <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">Pahami seluruh flow Satukelas</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-blue-100 sm:text-base">
                    Halaman ini menghubungkan struktur pembelajaran, webinar, user, pembayaran, assets, template certificate, sampai certificate yang sudah dicetak.
                </p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/20">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="{{ route('topics.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-4 py-3 text-sm font-semibold text-blue-800 shadow-lg transition hover:bg-blue-50">
                    <i class="fas fa-sitemap"></i> Kelola struktur
                </a>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-cyan-100 bg-cyan-50 p-5">
            <div class="flex items-center justify-between"><span class="text-sm font-medium text-cyan-700">Konten utama</span><i class="fas fa-layer-group text-cyan-600"></i></div>
            <p class="mt-3 text-3xl font-bold text-cyan-950">{{ number_format($metrics['learningPaths'] + $metrics['courses'] + $metrics['topics']) }}</p>
            <p class="mt-1 text-xs text-cyan-700">{{ number_format($metrics['learningPaths']) }} path, {{ number_format($metrics['courses']) }} course, {{ number_format($metrics['topics']) }} topic</p>
        </div>
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5">
            <div class="flex items-center justify-between"><span class="text-sm font-medium text-emerald-700">Event webinar</span><i class="fas fa-video text-emerald-600"></i></div>
            <p class="mt-3 text-3xl font-bold text-emerald-950">{{ number_format($metrics['webinars']) }}</p>
            <p class="mt-1 text-xs text-emerald-700">{{ number_format($metrics['publishedWebinars']) }} sudah published</p>
        </div>
        <div class="rounded-2xl border border-violet-100 bg-violet-50 p-5">
            <div class="flex items-center justify-between"><span class="text-sm font-medium text-violet-700">Peserta</span><i class="fas fa-users text-violet-600"></i></div>
            <p class="mt-3 text-3xl font-bold text-violet-950">{{ number_format($metrics['students']) }}</p>
            <p class="mt-1 text-xs text-violet-700">{{ number_format($metrics['paidStudents']) }} sudah bayar, {{ number_format($metrics['unpaidStudents']) }} belum bayar</p>
        </div>
        <div class="rounded-2xl border border-amber-100 bg-amber-50 p-5">
            <div class="flex items-center justify-between"><span class="text-sm font-medium text-amber-700">Certificate</span><i class="fas fa-file-circle-check text-amber-600"></i></div>
            <p class="mt-3 text-3xl font-bold text-amber-950">{{ number_format($metrics['printedCertificates']) }}</p>
            <p class="mt-1 text-xs text-amber-700">{{ number_format($metrics['templates']) }} template, {{ number_format($metrics['printedCertificateUsers']) }} user unik mencetak</p>
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-slate-900">1. Flow struktur pembelajaran</h2>
                <p class="mt-1 text-sm text-slate-500">Course regular disusun berjenjang. Webinar berjalan melalui jalur event tersendiri dan tidak masuk ke Topics.</p>
            </div>
            <span class="inline-flex w-fit items-center gap-2 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700"><i class="fas fa-arrow-right"></i> Urutan pengelolaan</span>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-3 lg:grid-cols-5 lg:gap-0">
            <a href="{{ route('learning_paths.index') }}" class="group relative rounded-2xl border border-cyan-200 bg-cyan-50 p-4 transition hover:-translate-y-0.5 hover:shadow-md lg:rounded-r-none">
                <span class="text-xs font-bold text-cyan-700">01</span><span class="ml-2 text-xs font-semibold uppercase tracking-wide text-cyan-700">Fondasi</span>
                <div class="mt-3 flex items-center justify-between gap-3"><i class="fas fa-route text-2xl text-cyan-600"></i><span class="text-2xl font-bold text-cyan-950">{{ number_format($metrics['learningPaths']) }}</span></div>
                <h3 class="mt-3 font-semibold text-cyan-950">Learning Path</h3><p class="mt-1 text-xs leading-5 text-cyan-800">Jalur besar yang mengelompokkan beberapa course.</p>
                <i class="fas fa-chevron-right absolute right-3 top-1/2 hidden -translate-y-1/2 text-cyan-400 lg:block"></i>
            </a>
            <a href="{{ route('courses.index') }}" class="group relative rounded-2xl border border-blue-200 bg-blue-50 p-4 transition hover:-translate-y-0.5 hover:shadow-md lg:rounded-none lg:border-l-0">
                <span class="text-xs font-bold text-blue-700">02</span><span class="ml-2 text-xs font-semibold uppercase tracking-wide text-blue-700">Produk</span>
                <div class="mt-3 flex items-center justify-between gap-3"><i class="fas fa-book-open-reader text-2xl text-blue-600"></i><span class="text-2xl font-bold text-blue-950">{{ number_format($metrics['courses']) }}</span></div>
                <h3 class="mt-3 font-semibold text-blue-950">Course regular</h3><p class="mt-1 text-xs leading-5 text-blue-800">Course dengan materi belajar dan dapat memiliki template certificate.</p>
                <i class="fas fa-chevron-right absolute right-3 top-1/2 hidden -translate-y-1/2 text-blue-400 lg:block"></i>
            </a>
            <a href="{{ route('topics.index') }}" class="group relative rounded-2xl border border-indigo-200 bg-indigo-50 p-4 transition hover:-translate-y-0.5 hover:shadow-md lg:rounded-none lg:border-l-0">
                <span class="text-xs font-bold text-indigo-700">03</span><span class="ml-2 text-xs font-semibold uppercase tracking-wide text-indigo-700">Kelompok</span>
                <div class="mt-3 flex items-center justify-between gap-3"><i class="fas fa-list-check text-2xl text-indigo-600"></i><span class="text-2xl font-bold text-indigo-950">{{ number_format($metrics['topics']) }}</span></div>
                <h3 class="mt-3 font-semibold text-indigo-950">Topic</h3><p class="mt-1 text-xs leading-5 text-indigo-800">Topik merangkum lesson dan quiz di dalam course.</p>
                <i class="fas fa-chevron-right absolute right-3 top-1/2 hidden -translate-y-1/2 text-indigo-400 lg:block"></i>
            </a>
            <a href="{{ route('lessons.index') }}" class="group relative rounded-2xl border border-violet-200 bg-violet-50 p-4 transition hover:-translate-y-0.5 hover:shadow-md lg:rounded-none lg:border-l-0">
                <span class="text-xs font-bold text-violet-700">04</span><span class="ml-2 text-xs font-semibold uppercase tracking-wide text-violet-700">Materi</span>
                <div class="mt-3 flex items-center justify-between gap-3"><i class="fas fa-play text-2xl text-violet-600"></i><span class="text-2xl font-bold text-violet-950">{{ number_format($metrics['lessons']) }}</span></div>
                <h3 class="mt-3 font-semibold text-violet-950">Lesson</h3><p class="mt-1 text-xs leading-5 text-violet-800">Materi berupa video, file, atau tipe material lainnya.</p>
                <i class="fas fa-chevron-right absolute right-3 top-1/2 hidden -translate-y-1/2 text-violet-400 lg:block"></i>
            </a>
            <a href="{{ route('topics.index') }}" class="group relative rounded-2xl border border-amber-200 bg-amber-50 p-4 transition hover:-translate-y-0.5 hover:shadow-md lg:rounded-l-none lg:border-l-0">
                <span class="text-xs font-bold text-amber-700">05</span><span class="ml-2 text-xs font-semibold uppercase tracking-wide text-amber-700">Evaluasi</span>
                <div class="mt-3 flex items-center justify-between gap-3"><i class="fas fa-file-circle-check text-2xl text-amber-600"></i><span class="text-2xl font-bold text-amber-950">{{ number_format($metrics['quizzes']) }}</span></div>
                <h3 class="mt-3 font-semibold text-amber-950">Quiz</h3><p class="mt-1 text-xs leading-5 text-amber-800">Pre Test, Post Test, dan quiz lain berada di dalam topic.</p>
            </a>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-start gap-3"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700"><i class="fas fa-video"></i></span><div><h2 class="font-semibold text-slate-900">2. Flow webinar</h2><p class="mt-1 text-sm text-slate-500">Webinar menggunakan course type Webinar dan tidak perlu dibuatkan topic, lesson, atau quiz.</p></div></div>
            <div class="mt-5 space-y-3">
                <a href="{{ route('courses.index', ['filter' => 'course_type']) }}" class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 transition hover:border-emerald-300 hover:bg-emerald-50"><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600"><i class="fas fa-tag"></i></span><div class="min-w-0 flex-1"><p class="text-sm font-semibold text-slate-800">Course type Webinar</p><p class="text-xs text-slate-500">{{ number_format($metrics['webinarCourses']) }} course terdeteksi</p></div><i class="fas fa-arrow-right text-xs text-slate-400"></i></a>
                <a href="{{ route('webinars.index') }}" class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 transition hover:border-emerald-300 hover:bg-emerald-50"><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600"><i class="fas fa-calendar-days"></i></span><div class="min-w-0 flex-1"><p class="text-sm font-semibold text-slate-800">Jadwal dan publikasi webinar</p><p class="text-xs text-slate-500">{{ number_format($metrics['webinars']) }} event, {{ number_format($metrics['publishedWebinars']) }} published</p></div><i class="fas fa-arrow-right text-xs text-slate-400"></i></a>
                <a href="{{ route('students.paid') }}" class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 transition hover:border-emerald-300 hover:bg-emerald-50"><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-50 text-violet-600"><i class="fas fa-user-check"></i></span><div class="min-w-0 flex-1"><p class="text-sm font-semibold text-slate-800">Peserta mengikuti webinar</p><p class="text-xs text-slate-500">Gunakan data user dan membership untuk melihat akses peserta.</p></div><i class="fas fa-arrow-right text-xs text-slate-400"></i></a>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-start gap-3"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-700"><i class="fas fa-users"></i></span><div><h2 class="font-semibold text-slate-900">3. Flow user dan akses</h2><p class="mt-1 text-sm text-slate-500">Status pembayaran menentukan kelompok pengelolaan peserta.</p></div></div>
            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3 sm:items-center">
                <a href="{{ route('superadmin.index') }}" class="rounded-xl border border-blue-200 bg-blue-50 p-3 transition hover:shadow-md"><i class="fas fa-user-group text-blue-600"></i><p class="mt-2 text-sm font-semibold text-blue-950">Semua user</p><p class="mt-1 text-xs text-blue-700">{{ number_format($metrics['users']) }} user</p></a>
                <div class="hidden text-center text-slate-300 sm:block"><i class="fas fa-arrow-right"></i></div>
                <div class="space-y-2"><a href="{{ route('students.paid') }}" class="flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 p-3 transition hover:shadow-md"><span><i class="fas fa-check-circle mr-2 text-emerald-600"></i><span class="text-sm font-semibold text-emerald-950">Sudah bayar</span></span><b class="text-emerald-800">{{ number_format($metrics['paidStudents']) }}</b></a><a href="{{ route('students.unpaid') }}" class="flex items-center justify-between rounded-xl border border-rose-200 bg-rose-50 p-3 transition hover:shadow-md"><span><i class="fas fa-clock mr-2 text-rose-600"></i><span class="text-sm font-semibold text-rose-950">Belum bayar</span></span><b class="text-rose-800">{{ number_format($metrics['unpaidStudents']) }}</b></a></div>
            </div>
        </section>
    </div>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"><div><h2 class="font-semibold text-slate-900">4. Flow assets sampai certificate</h2><p class="mt-1 text-sm text-slate-500">Template certificate adalah konfigurasi pada tabel certificate_template. Certificate adalah hasil yang sudah dicetak untuk user.</p></div><a href="{{ route('assets.index') }}" class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50"><i class="fas fa-image"></i> Kelola assets</a></div>
        <div class="mt-6 grid grid-cols-1 gap-3 lg:grid-cols-4 lg:gap-0">
            <a href="{{ route('assets.index') }}" class="rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-violet-300 hover:bg-violet-50 lg:rounded-r-none"><div class="flex items-center justify-between"><i class="fas fa-file-image text-2xl text-violet-600"></i><span class="text-2xl font-bold text-slate-900">{{ number_format($metrics['templateAssets']) }}</span></div><h3 class="mt-3 font-semibold text-slate-800">Asset template</h3><p class="mt-1 text-xs leading-5 text-slate-500">File dasar yang diupload ke storage.</p></a>
            <a href="{{ route('assets.index') }}" class="rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-blue-300 hover:bg-blue-50 lg:rounded-none lg:border-l-0"><div class="flex items-center justify-between"><i class="fas fa-certificate text-2xl text-blue-600"></i><span class="text-2xl font-bold text-slate-900">{{ number_format($metrics['templates']) }}</span></div><h3 class="mt-3 font-semibold text-slate-800">Certificate template</h3><p class="mt-1 text-xs leading-5 text-slate-500">{{ number_format($metrics['completeTemplates']) }} lengkap, {{ number_format($metrics['incompleteTemplates']) }} perlu dibenahi.</p></a>
            <a href="{{ route('certificate.index') }}" class="rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-amber-300 hover:bg-amber-50 lg:rounded-none lg:border-l-0"><div class="flex items-center justify-between"><i class="fas fa-print text-2xl text-amber-600"></i><span class="text-2xl font-bold text-slate-900">{{ number_format($metrics['printedCertificates']) }}</span></div><h3 class="mt-3 font-semibold text-slate-800">Certificate tercetak</h3><p class="mt-1 text-xs leading-5 text-slate-500">{{ number_format($metrics['printedCertificateUsers']) }} user unik sudah mencetak.</p></a>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 lg:rounded-l-none lg:border-l-0"><div class="flex items-center justify-between"><i class="fas fa-award text-2xl text-emerald-600"></i><span class="text-2xl font-bold text-emerald-950">{{ number_format($metrics['webinarCertificateUsers']) }}</span></div><h3 class="mt-3 font-semibold text-emerald-950">User webinar bersertifikat</h3><p class="mt-1 text-xs leading-5 text-emerald-800">User unik dengan certificate dari course webinar.</p></div>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-5">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 xl:col-span-3">
            <div class="flex items-start gap-3"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-700"><i class="fas fa-stethoscope"></i></span><div><h2 class="font-semibold text-slate-900">Pemeriksaan flow</h2><p class="mt-1 text-sm text-slate-500">Gunakan daftar ini untuk mengetahui bagian yang perlu dilengkapi.</p></div></div>
            <div class="mt-5 space-y-3">
                <a href="{{ route('topics.index') }}" class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 transition hover:bg-slate-50"><span class="flex h-8 w-8 items-center justify-center rounded-lg {{ $gaps['coursesWithoutTopics'] > 0 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}"><i class="fas {{ $gaps['coursesWithoutTopics'] > 0 ? 'fa-triangle-exclamation' : 'fa-check' }}"></i></span><div class="min-w-0 flex-1"><p class="text-sm font-semibold text-slate-700">Course tanpa topic</p><p class="text-xs text-slate-500">{{ $gaps['coursesWithoutTopics'] > 0 ? number_format($gaps['coursesWithoutTopics']) . ' course perlu disusun' : 'Semua course sudah memiliki topic' }}</p></div><i class="fas fa-arrow-right text-xs text-slate-400"></i></a>
                <a href="{{ route('topics.index') }}" class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 transition hover:bg-slate-50"><span class="flex h-8 w-8 items-center justify-center rounded-lg {{ $gaps['topicsWithoutContent'] > 0 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}"><i class="fas {{ $gaps['topicsWithoutContent'] > 0 ? 'fa-triangle-exclamation' : 'fa-check' }}"></i></span><div class="min-w-0 flex-1"><p class="text-sm font-semibold text-slate-700">Topic tanpa lesson dan quiz</p><p class="text-xs text-slate-500">{{ $gaps['topicsWithoutContent'] > 0 ? number_format($gaps['topicsWithoutContent']) . ' topic belum memiliki isi' : 'Semua topic sudah memiliki materi atau quiz' }}</p></div><i class="fas fa-arrow-right text-xs text-slate-400"></i></a>
                <a href="{{ route('assets.index') }}" class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 transition hover:bg-slate-50"><span class="flex h-8 w-8 items-center justify-center rounded-lg {{ $metrics['incompleteTemplates'] > 0 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}"><i class="fas {{ $metrics['incompleteTemplates'] > 0 ? 'fa-triangle-exclamation' : 'fa-check' }}"></i></span><div class="min-w-0 flex-1"><p class="text-sm font-semibold text-slate-700">Template certificate belum lengkap</p><p class="text-xs text-slate-500">{{ $metrics['incompleteTemplates'] > 0 ? number_format($metrics['incompleteTemplates']) . ' konfigurasi belum lengkap' : 'Semua template sudah lengkap' }}</p></div><i class="fas fa-arrow-right text-xs text-slate-400"></i></a>
                <a href="{{ route('webinars.index') }}" class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 transition hover:bg-slate-50"><span class="flex h-8 w-8 items-center justify-center rounded-lg {{ $gaps['unlinkedWebinars'] > 0 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}"><i class="fas {{ $gaps['unlinkedWebinars'] > 0 ? 'fa-triangle-exclamation' : 'fa-check' }}"></i></span><div class="min-w-0 flex-1"><p class="text-sm font-semibold text-slate-700">Webinar tanpa course</p><p class="text-xs text-slate-500">{{ $gaps['unlinkedWebinars'] > 0 ? number_format($gaps['unlinkedWebinars']) . ' webinar belum terhubung' : 'Semua webinar sudah terhubung ke course' }}</p></div><i class="fas fa-arrow-right text-xs text-slate-400"></i></a>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 xl:col-span-2">
            <div class="flex items-start gap-3"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700"><i class="fas fa-tags"></i></span><div><h2 class="font-semibold text-slate-900">Course type</h2><p class="mt-1 text-sm text-slate-500">Pembagian course menentukan flow yang digunakan.</p></div></div>
            <div class="mt-5 space-y-3">
                @forelse($courseTypes as $courseType)
                    <div class="flex items-center gap-3"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500"><i class="fas fa-book"></i></span><div class="min-w-0 flex-1"><p class="truncate text-sm font-semibold text-slate-700">{{ $courseType->title }}</p><div class="mt-1 h-1.5 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-blue-500" style="width: {{ $courseTypes->max('courses_count') > 0 ? min(100, round(($courseType->courses_count / $courseTypes->max('courses_count')) * 100)) : 0 }}%"></div></div></div><span class="shrink-0 text-sm font-bold text-slate-700">{{ number_format($courseType->courses_count) }}</span></div>
                @empty
                    <p class="py-6 text-center text-sm text-slate-500">Belum ada course type.</p>
                @endforelse
            </div>
            <div class="mt-5 rounded-xl bg-slate-50 p-3 text-xs leading-5 text-slate-600"><i class="fas fa-circle-info mr-1 text-blue-600"></i> Hanya course type Webinar yang diarahkan ke flow webinar. Course type lainnya tetap dapat memakai struktur topic, lesson, quiz, dan certificate.</div>
        </section>
    </div>
</div>
@endsection
