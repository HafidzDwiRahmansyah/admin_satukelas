@extends('layouts.app')

@section('content')
<div class="p-10">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-700">Learning Paths</h2>
        <button onclick="openAddModal()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Tambah Learning Path</button>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full text-sm">
            <thead class="bg-blue-100">
                <tr>
                    <th class="p-3">Title</th>
                    <th class="p-3">Price</th>
                    <th class="p-3">Thumbnail</th>
                    <th class="p-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($learningPaths as $lp)
                <tr class="border-b hover:bg-blue-50">
                    <td class="p-3">{{ $lp->title }}</td>
                    <td class="p-3">
                        Rp {{ number_format($lp->price, 0, ',', '.') }}
                    </td>
                    <td class="p-3">
                        @if($lp->thumbnail)
                        <img src="{{ $lp->thumbnail }}" class="h-10 w-10 object-cover rounded">
                        @else
                        -
                        @endif
                    </td>
                    <td class="p-3 space-x-2">
                        <button onclick="openEditModal({{ $lp->id }}, '{{ $lp->title }}', '{{ $lp->price }}', '{{ $lp->thumbnail }}', '{{ $lp->description }}', '{{ $lp->objective }}', '{{ $lp->certificate_url }}')"
                            class="px-2 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500">Edit</button>

                        <button onclick="confirmDelete({{ $lp->id }})"
                            class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah/Edit -->
    <div id="learningPathModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative">
            <button onclick="closeLearningPathModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
            <h3 id="modalTitle" class="text-xl font-bold mb-4">Tambah Learning Path</h3>
            <form id="learningPathForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="mb-3">
                    <label class="block mb-1 font-medium">Title</label>
                    <input type="text" name="title" id="lpTitle" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label class="block mb-1 font-medium">Price</label>
                    <input type="text" name="price" id="lpPrice" class="w-full border rounded px-3 py-2" value="0">
                </div>
                <div class="mb-3">
                    <label class="block mb-1 font-medium">Thumbnail URL</label>
                    <input type="text" name="thumbnail" id="lpThumbnail" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-3">
                    <label class="block mb-1 font-medium">Description</label>
                    <textarea name="description" id="lpDescription" class="w-full border rounded px-3 py-2"></textarea>
                </div>
                <div class="mb-3">
                    <label class="block mb-1 font-medium">Objective</label>
                    <textarea name="objective" id="lpObjective" class="w-full border rounded px-3 py-2"></textarea>
                </div>
                <div class="mb-3">
                    <label class="block mb-1 font-medium">Certificate URL</label>
                    <input type="text" name="certificate_url" id="lpCertificate" class="w-full border rounded px-3 py-2">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-6">
        {{ $learningPaths->links() }}
    </div>
</div>

<script>
    document.getElementById('lpPrice').addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, ""); // ambil angka saja
        if (value) {
            this.value = new Intl.NumberFormat('id-ID').format(value);
        } else {
            this.value = "";
        }
    });

    // sebelum submit → hapus format (biar database simpan angka saja)
    document.getElementById('learningPathForm').addEventListener('submit', function() {
        let input = document.getElementById('lpPrice');
        input.value = input.value.replace(/\D/g, "");
    });
    // Tambah Modal
    function openAddModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Learning Path';
        const form = document.getElementById('learningPathForm');
        form.action = "{{ route('learning_paths.store') }}";
        document.getElementById('formMethod').value = "POST";
        form.reset();
        document.getElementById('learningPathModal').classList.remove('hidden');
    }

    // Edit Modal
    function openEditModal(id, title, price, thumbnail, description, objective, certificate_url) {
        document.getElementById('modalTitle').innerText = 'Edit Learning Path';
        const form = document.getElementById('learningPathForm');
        form.action = `/learning_paths/${id}`;
        document.getElementById('formMethod').value = "PUT";
        document.getElementById('lpTitle').value = title;
        document.getElementById('lpPrice').value = price;
        document.getElementById('lpThumbnail').value = thumbnail;
        document.getElementById('lpDescription').value = description;
        document.getElementById('lpObjective').value = objective;
        document.getElementById('lpCertificate').value = certificate_url;
        document.getElementById('learningPathModal').classList.remove('hidden');
    }

    function closeLearningPathModal() {
        document.getElementById('learningPathModal').classList.add('hidden');
    }

    // Hapus
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data Learning Path akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/learning_paths/${id}`;
                form.innerHTML = `
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection