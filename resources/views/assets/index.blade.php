@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="mb-2 flex items-center gap-2 text-sm text-slate-500"><i class="fas fa-certificate text-violet-600"></i><span>Manajemen Assets</span><i class="fas fa-chevron-right text-xs text-slate-400"></i><span>Certificate Template</span></div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Certificate Template Management</h1>
            <p class="mt-1 max-w-2xl text-sm text-slate-500">Daftar ini mengambil data langsung dari tabel <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs">certificate_template</code>, bukan dari seluruh certificate yang sudah dicetak.</p>
        </div>
        <a href="#upload-asset" class="inline-flex items-center justify-center gap-2 rounded-xl bg-violet-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-violet-700 focus:outline-none focus:ring-4 focus:ring-violet-100"><i class="fas fa-cloud-arrow-up"></i> Upload Template Asset</a>
    </div>

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-5">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5"><div class="flex items-center justify-between"><p class="text-xs font-medium text-slate-500 sm:text-sm">Total Template</p><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-50 text-violet-600"><i class="fas fa-layer-group"></i></span></div><p class="mt-3 text-2xl font-bold text-slate-900">{{ number_format($totalTemplateCount) }}</p></div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5"><div class="flex items-center justify-between"><p class="text-xs font-medium text-slate-500 sm:text-sm">Partisipasi</p><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600"><i class="fas fa-users"></i></span></div><p class="mt-3 text-2xl font-bold text-slate-900">{{ number_format($participationTemplateCount) }}</p><p class="mt-1 text-xs text-slate-400">jenis webinar</p></div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5"><div class="flex items-center justify-between"><p class="text-xs font-medium text-slate-500 sm:text-sm">Kompetensi</p><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600"><i class="fas fa-graduation-cap"></i></span></div><p class="mt-3 text-2xl font-bold text-slate-900">{{ number_format($competenceTemplateCount) }}</p><p class="mt-1 text-xs text-slate-400">jenis course</p></div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5"><div class="flex items-center justify-between"><p class="text-xs font-medium text-slate-500 sm:text-sm">Konfigurasi Lengkap</p><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600"><i class="fas fa-circle-check"></i></span></div><p class="mt-3 text-2xl font-bold text-slate-900">{{ number_format($completeTemplateCount) }}</p></div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5"><div class="flex items-center justify-between"><p class="text-xs font-medium text-slate-500 sm:text-sm">Belum Lengkap</p><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-50 text-rose-600"><i class="fas fa-triangle-exclamation"></i></span></div><p class="mt-3 text-2xl font-bold text-slate-900">{{ number_format($incompleteTemplateCount) }}</p><p class="mt-1 text-xs text-slate-400">nama atau asset belum ada</p></div>
    </div>

    <section id="upload-asset" class="rounded-2xl border border-violet-100 bg-gradient-to-br from-violet-50 via-white to-blue-50 p-5 shadow-sm sm:p-6">
        <div class="mb-5 flex items-start gap-3"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-600 text-white shadow-sm"><i class="fas fa-cloud-arrow-up"></i></span><div><h2 class="font-semibold text-slate-900">Upload asset template</h2><p class="mt-1 text-xs text-slate-500">Upload file dasar template. Setelah itu asset perlu dikaitkan ke baris pada tabel <code>certificate_template</code>.</p></div></div>
        <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_auto]">@csrf<label class="flex cursor-pointer items-center gap-3 rounded-xl border border-dashed border-slate-300 bg-white px-4 py-3 transition hover:border-violet-400 hover:bg-violet-50/50"><i class="fas fa-file-arrow-up text-violet-600"></i><span id="fileLabel" class="truncate text-sm text-slate-500">Pilih file template</span><input id="assetFile" type="file" name="file" required accept=".jpg,.jpeg,.png,.gif,.webp,.pdf" class="hidden"></label><input type="hidden" name="bucket" value="certificate_templates"><button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700"><i class="fas fa-upload"></i> Upload</button></form>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        <form action="{{ route('assets.index') }}" method="GET" class="grid grid-cols-1 gap-3 lg:grid-cols-[minmax(0,1fr)_220px_220px_auto_auto]">
            <div class="relative"><i class="fas fa-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i><input type="text" name="search" value="{{ request('search') }}" placeholder="Cari course, nama asset, atau jenis certificate..." class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-3 text-sm outline-none transition focus:border-violet-500 focus:bg-white focus:ring-4 focus:ring-violet-100"></div>
            <select name="certificate_type" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-violet-500 focus:bg-white focus:ring-4 focus:ring-violet-100"><option value="all">Semua jenis</option><option value="Partisipasi" {{ request('certificate_type') === 'Partisipasi' ? 'selected' : '' }}>Partisipasi</option><option value="Kompetensi" {{ request('certificate_type') === 'Kompetensi' ? 'selected' : '' }}>Kompetensi</option></select>
            <select name="template_status" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-violet-500 focus:bg-white focus:ring-4 focus:ring-violet-100"><option value="">Semua status</option><option value="complete" {{ request('template_status') === 'complete' ? 'selected' : '' }}>Konfigurasi lengkap</option><option value="incomplete" {{ request('template_status') === 'incomplete' ? 'selected' : '' }}>Nama/asset kosong</option></select>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700"><i class="fas fa-filter"></i> Filter</button>
            @if(request()->filled('search') || request()->filled('certificate_type') || request()->filled('template_status'))
            <a href="{{ route('assets.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"><i class="fas fa-rotate-left"></i> Reset</a>
            @endif
        </form>
    </section>

    <div class="flex items-end justify-between gap-3"><div><h2 class="font-semibold text-slate-900">Daftar Certificate Template</h2><p class="mt-1 text-xs text-slate-500">Menampilkan {{ $templates->count() }} dari {{ $templates->total() }} konfigurasi</p></div><span class="rounded-full bg-violet-50 px-3 py-1 text-xs font-semibold text-violet-700">Halaman {{ $templates->currentPage() }}</span></div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @forelse($templates as $template)
        @php
            $courseName = $template->course?->title;
            $assetName = $template->asset?->file_name;
            $assetUrl = $template->asset?->url;
            $extension = strtolower($template->asset?->extension ?: pathinfo($assetName ?: '', PATHINFO_EXTENSION));
            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
            $isComplete = filled($courseName) && filled($assetName);
        @endphp
        <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-violet-200 hover:shadow-md">
            <div class="relative flex h-48 items-center justify-center overflow-hidden bg-slate-100">
                @if($assetUrl && $isImage)
                <img src="{{ $assetUrl }}" alt="{{ $assetName ?: 'Template certificate' }}" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                @else
                <div class="text-center"><i class="fas {{ $extension === 'pdf' ? 'fa-file-pdf text-rose-500' : 'fa-certificate text-violet-500' }} text-5xl"></i><p class="mt-3 text-xs font-semibold uppercase text-slate-500">{{ $extension ?: 'asset kosong' }}</p></div>
                @endif
                <div class="absolute left-3 top-3"><span class="rounded-full bg-white/90 px-2.5 py-1 text-[11px] font-semibold text-slate-700 shadow-sm">Template #{{ $template->id }}</span></div>
                <div class="absolute right-3 top-3"><span class="rounded-full {{ $template->certificate_type === 'Partisipasi' ? 'bg-blue-600' : 'bg-emerald-600' }} px-2.5 py-1 text-[11px] font-semibold text-white shadow-sm">{{ $template->certificate_type ?: 'Jenis kosong' }}</span></div>
            </div>
            <div class="space-y-4 p-4">
                <div><h3 class="truncate text-sm font-semibold text-slate-800" title="{{ $courseName ?: 'Nama course belum ada' }}">{{ $courseName ?: 'Nama course belum ada' }}</h3><p class="mt-1 text-xs text-slate-400">Course ID: {{ $template->course_id ?: 'Belum diatur' }}</p></div>
                <div class="space-y-2 rounded-xl bg-slate-50 p-3 text-xs"><div class="flex items-start justify-between gap-3"><span class="text-slate-400">Nama asset</span><span class="max-w-[180px] truncate text-right font-medium {{ $assetName ? 'text-slate-700' : 'text-rose-600' }}">{{ $assetName ?: 'Nama asset belum ada' }}</span></div><div class="flex items-center justify-between gap-3"><span class="text-slate-400">Asset ID</span><span class="font-medium {{ $template->asset_id ? 'text-slate-700' : 'text-rose-600' }}">{{ $template->asset_id ?: 'Belum diatur' }}</span></div></div>
                <div class="flex items-center justify-between gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full {{ $isComplete ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }} px-2.5 py-1 text-xs font-semibold">
                        @if($isComplete)
                        <i class="fas fa-check"></i> Konfigurasi lengkap
                        @else
                        <i class="fas fa-triangle-exclamation"></i> Nama/asset kosong
                        @endif
                    </span>
                    @if($assetUrl)
                    <a href="{{ $assetUrl }}" target="_blank" rel="noopener noreferrer" class="text-xs font-semibold text-violet-600 hover:text-violet-800"><i class="fas fa-arrow-up-right-from-square mr-1"></i>Buka</a>
                    @endif
                </div>
                @if(!$isComplete)
                <div class="rounded-lg border border-rose-100 bg-rose-50 px-3 py-2 text-xs text-rose-700">Template ini belum lengkap karena {{ !$courseName ? 'nama course' : 'nama asset' }} belum tersedia.</div>
                @endif
            </div>
        </article>
        @empty
        <div class="col-span-full rounded-2xl border border-slate-200 bg-white px-5 py-16 text-center shadow-sm"><i class="fas fa-certificate text-4xl text-slate-300"></i><p class="mt-4 font-semibold text-slate-700">Certificate template tidak ditemukan</p><p class="mt-1 text-sm text-slate-500">Coba ubah filter atau tambahkan konfigurasi pada tabel certificate_template.</p></div>
        @endforelse
    </div>

    <div>{{ $templates->links() }}</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const assetFile = document.getElementById('assetFile');
    if (assetFile) assetFile.addEventListener('change', function () { document.getElementById('fileLabel').textContent = this.files[0]?.name || 'Pilih file template'; });
    @if(session('success'))
    Swal.fire({ icon: 'success', title: 'Berhasil', text: @js(session('success')), timer: 1800, showConfirmButton: false });
    @endif
    @if(session('error'))
    Swal.fire({ icon: 'error', title: 'Tidak dapat diproses', text: @js(session('error')) });
    @endif
</script>
@endsection
