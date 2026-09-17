@extends('layouts.app')

@section('title', 'Learning Path')

@section('content')
    @php
        $pageItems = collect($learningPaths->items());
        $thumbnailCount = $pageItems->filter(fn ($item) => filled($item->thumbnail))->count();
    @endphp

    <div class="mx-auto max-w-7xl space-y-6">
        <section class="overflow-hidden rounded-3xl bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 p-6 text-white shadow-xl sm:p-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-blue-50"><i class="fas fa-route"></i> Manajemen pembelajaran</div>
                    <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">Learning Path</h1>
                    <p class="mt-2 max-w-xl text-sm leading-6 text-blue-100 sm:text-base">Susun jalur belajar yang terarah, mudah dipantau, dan siap dikembangkan menjadi beberapa course.</p>
                </div>
                <button type="button" onclick="openAddModal()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-4 py-3 text-sm font-semibold text-blue-700 shadow-lg transition hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-white/30"><i class="fas fa-plus"></i> Tambah Learning Path</button>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex items-center justify-between"><span class="text-sm font-medium text-slate-500">Total learning path</span><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i class="fas fa-layer-group"></i></span></div><p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($learningPaths->total()) }}</p><p class="mt-1 text-xs text-slate-400">Semua data yang tersedia</p></div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex items-center justify-between"><span class="text-sm font-medium text-slate-500">Di halaman ini</span><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600"><i class="fas fa-list"></i></span></div><p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($learningPaths->count()) }}</p><p class="mt-1 text-xs text-slate-400">Halaman {{ $learningPaths->currentPage() }}</p></div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex items-center justify-between"><span class="text-sm font-medium text-slate-500">Memiliki thumbnail</span><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600"><i class="fas fa-image"></i></span></div><p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($thumbnailCount) }}</p><p class="mt-1 text-xs text-slate-400">Dari data halaman aktif</p></div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between"><div><h2 class="font-semibold text-slate-900">Daftar Learning Path</h2><p class="mt-1 text-xs text-slate-500">Kelola informasi, thumbnail, dan tujuan setiap jalur belajar.</p></div><span class="inline-flex w-fit items-center gap-2 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700"><i class="fas fa-database"></i> {{ number_format($learningPaths->total()) }} data</span></div>

            <div class="hidden overflow-x-auto lg:block">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-6 py-4 font-semibold">Learning Path</th><th class="px-6 py-4 font-semibold">Harga</th><th class="px-6 py-4 font-semibold">Thumbnail</th><th class="px-6 py-4 text-right font-semibold">Aksi</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($learningPaths as $lp)
                            @php
                                $lpPayload = ['id' => $lp->id, 'title' => $lp->title, 'price' => $lp->price, 'thumbnail' => $lp->thumbnail, 'description' => $lp->description, 'objective' => $lp->objective, 'certificate_url' => $lp->certificate_url];
                            @endphp
                            <tr class="transition hover:bg-blue-50/50">
                                <td class="px-6 py-4"><div class="flex min-w-[260px] items-center gap-3"><div class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-blue-100 text-blue-700">@if($lp->thumbnail)<img src="{{ $lp->thumbnail }}" alt="{{ $lp->title }}" class="h-full w-full object-cover" loading="lazy">@else<i class="fas fa-route"></i>@endif</div><div class="min-w-0"><p class="truncate font-semibold text-slate-800">{{ $lp->title }}</p><p class="mt-1 text-xs text-slate-400">ID #{{ $lp->id }}</p></div></div></td>
                                <td class="whitespace-nowrap px-6 py-4 font-semibold text-slate-700">Rp {{ number_format($lp->price ?? 0, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">@if($lp->thumbnail)<span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700"><i class="fas fa-check"></i> Tersedia</span>@else<span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500"><i class="fas fa-minus"></i> Belum ada</span>@endif</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right"><div class="inline-flex items-center gap-2"><button type="button" data-learning-path='@js($lpPayload)' class="edit-learning-path inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700" title="Edit"><i class="fas fa-pen"></i></button><button type="button" data-delete-id="{{ $lp->id }}" class="delete-learning-path inline-flex h-9 w-9 items-center justify-center rounded-lg border border-rose-100 text-rose-500 transition hover:bg-rose-50 hover:text-rose-700" title="Hapus"><i class="fas fa-trash"></i></button></div></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-16 text-center"><i class="fas fa-route text-3xl text-slate-300"></i><p class="mt-3 font-semibold text-slate-700">Belum ada learning path</p><p class="mt-1 text-sm text-slate-500">Tambahkan learning path pertama untuk memulai.</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="space-y-3 p-4 lg:hidden">
                @forelse($learningPaths as $lp)
                    @php
                        $lpPayload = ['id' => $lp->id, 'title' => $lp->title, 'price' => $lp->price, 'thumbnail' => $lp->thumbnail, 'description' => $lp->description, 'objective' => $lp->objective, 'certificate_url' => $lp->certificate_url];
                    @endphp
                    <article class="rounded-2xl border border-slate-200 p-4"><div class="flex items-start gap-3"><div class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-blue-100 text-blue-700">@if($lp->thumbnail)<img src="{{ $lp->thumbnail }}" alt="{{ $lp->title }}" class="h-full w-full object-cover" loading="lazy">@else<i class="fas fa-route"></i>@endif</div><div class="min-w-0 flex-1"><h3 class="truncate font-semibold text-slate-800">{{ $lp->title }}</h3><p class="mt-1 text-xs text-slate-400">ID #{{ $lp->id }}</p></div><span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">Learning</span></div><div class="mt-4 grid grid-cols-2 gap-3 rounded-xl bg-slate-50 p-3 text-sm"><div><p class="text-xs text-slate-400">Harga</p><p class="mt-1 font-semibold text-slate-700">Rp {{ number_format($lp->price ?? 0, 0, ',', '.') }}</p></div><div><p class="text-xs text-slate-400">Thumbnail</p><p class="mt-1 font-semibold {{ $lp->thumbnail ? 'text-emerald-600' : 'text-slate-500' }}">{{ $lp->thumbnail ? 'Tersedia' : 'Belum ada' }}</p></div></div><div class="mt-4 flex gap-2"><button type="button" data-learning-path='@js($lpPayload)' class="edit-learning-path flex-1 rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-blue-50 hover:text-blue-700"><i class="fas fa-pen mr-1"></i> Edit</button><button type="button" data-delete-id="{{ $lp->id }}" class="delete-learning-path flex-1 rounded-lg border border-rose-100 px-3 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50"><i class="fas fa-trash mr-1"></i> Hapus</button></div></article>
                @empty
                    <div class="px-4 py-12 text-center"><i class="fas fa-route text-3xl text-slate-300"></i><p class="mt-3 font-semibold text-slate-700">Belum ada learning path</p></div>
                @endforelse
            </div>

            @if($learningPaths->hasPages())<div class="border-t border-slate-100 px-5 py-4">{{ $learningPaths->links() }}</div>@endif
        </section>
    </div>

    <div id="learningPathModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
        <div class="max-h-[calc(100vh-2rem)] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-2xl">
            <div class="sticky top-0 z-10 flex items-center justify-between border-b border-slate-100 bg-white px-5 py-4 sm:px-6"><div><p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Learning Path</p><h2 id="modalTitle" class="mt-1 text-lg font-bold text-slate-900">Tambah Learning Path</h2></div><button type="button" onclick="closeLearningPathModal()" class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700"><i class="fas fa-times"></i></button></div>
            <form id="learningPathForm" method="POST" class="space-y-4 p-5 sm:p-6">@csrf<input type="hidden" name="_method" id="formMethod" value="POST"><div><label for="lpTitle" class="mb-1.5 block text-sm font-semibold text-slate-700">Judul</label><input type="text" name="title" id="lpTitle" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required></div><div class="grid gap-4 sm:grid-cols-2"><div><label for="lpPrice" class="mb-1.5 block text-sm font-semibold text-slate-700">Harga</label><input type="text" name="price" id="lpPrice" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" value="0"></div><div><label for="lpThumbnail" class="mb-1.5 block text-sm font-semibold text-slate-700">Thumbnail URL</label><input type="url" name="thumbnail" id="lpThumbnail" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"></div></div><div><label for="lpDescription" class="mb-1.5 block text-sm font-semibold text-slate-700">Deskripsi</label><textarea name="description" id="lpDescription" rows="3" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"></textarea></div><div><label for="lpObjective" class="mb-1.5 block text-sm font-semibold text-slate-700">Tujuan pembelajaran</label><textarea name="objective" id="lpObjective" rows="3" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"></textarea></div><div><label for="lpCertificate" class="mb-1.5 block text-sm font-semibold text-slate-700">Certificate URL <span class="font-normal text-slate-400">(opsional)</span></label><input type="url" name="certificate_url" id="lpCertificate" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"></div><div class="flex flex-col-reverse gap-2 border-t border-slate-100 pt-4 sm:flex-row sm:justify-end"><button type="button" onclick="closeLearningPathModal()" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</button><button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"><i class="fas fa-save mr-1"></i> Simpan</button></div></form>
        </div>
    </div>

    <script>
        const learningPathModal = document.getElementById('learningPathModal');
        const learningPathForm = document.getElementById('learningPathForm');
        const lpPrice = document.getElementById('lpPrice');
        function formatLearningPathPrice(value) { const digits = String(value ?? '').replace(/\D/g, ''); return digits ? new Intl.NumberFormat('id-ID').format(digits) : ''; }
        function openAddModal() { document.getElementById('modalTitle').textContent = 'Tambah Learning Path'; learningPathForm.reset(); learningPathForm.action = @js(route('learning_paths.store')); document.getElementById('formMethod').value = 'POST'; lpPrice.value = '0'; learningPathModal.classList.remove('hidden'); learningPathModal.classList.add('flex'); document.getElementById('lpTitle').focus(); }
        function openEditModal(item) { document.getElementById('modalTitle').textContent = 'Edit Learning Path'; learningPathForm.action = `/learning_paths/${item.id}`; document.getElementById('formMethod').value = 'PUT'; document.getElementById('lpTitle').value = item.title ?? ''; lpPrice.value = formatLearningPathPrice(item.price); document.getElementById('lpThumbnail').value = item.thumbnail ?? ''; document.getElementById('lpDescription').value = item.description ?? ''; document.getElementById('lpObjective').value = item.objective ?? ''; document.getElementById('lpCertificate').value = item.certificate_url ?? ''; learningPathModal.classList.remove('hidden'); learningPathModal.classList.add('flex'); document.getElementById('lpTitle').focus(); }
        function closeLearningPathModal() { learningPathModal.classList.add('hidden'); learningPathModal.classList.remove('flex'); }
        lpPrice.addEventListener('input', () => { lpPrice.value = formatLearningPathPrice(lpPrice.value); });
        learningPathForm.addEventListener('submit', () => { lpPrice.value = lpPrice.value.replace(/\D/g, '') || '0'; });
        document.querySelectorAll('.edit-learning-path').forEach((button) => button.addEventListener('click', () => openEditModal(JSON.parse(button.dataset.learningPath))));
        document.querySelectorAll('.delete-learning-path').forEach((button) => button.addEventListener('click', () => { Swal.fire({ title: 'Hapus learning path?', text: 'Data yang dihapus tidak dapat dikembalikan.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonText: 'Batal', confirmButtonText: 'Ya, hapus' }).then((result) => { if (!result.isConfirmed) return; const form = document.createElement('form'); form.method = 'POST'; form.action = `/learning_paths/${button.dataset.deleteId}`; form.innerHTML = `@csrf<input type="hidden" name="_method" value="DELETE">`; document.body.appendChild(form); form.submit(); }); }));
        learningPathModal.addEventListener('click', (event) => { if (event.target === learningPathModal) closeLearningPathModal(); });
        document.addEventListener('keydown', (event) => { if (event.key === 'Escape') closeLearningPathModal(); });
    </script>
@endsection
