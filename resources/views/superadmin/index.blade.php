@extends('layouts.app')

@section('content')
<div class="px-6 py-4">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-600">Management Superadmin</h2>
        <button onclick="openCreateModal()"
            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
            Tambah User
        </button>
    </div>

    {{-- Bulk Delete Form --}}
    <form id="bulkDeleteForm" method="POST" action="{{ route('superadmin.bulkDelete') }}">
        @csrf
        @method('DELETE')

        <div class="mb-4">
            <button type="submit" id="deleteSelectedBtn"
                class="px-3 py-2 bg-red-600 text-white text-sm rounded disabled:opacity-50"
                disabled>
                Hapus Terpilih
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="w-full text-left border border-gray-200">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-3 py-2"><input type="checkbox" id="selectAll"></th>
                        <th class="px-3 py-2">#</th>
                        <th class="px-3 py-2">Nama</th>
                        <th class="px-3 py-2">Email</th>
                        <th class="px-3 py-2">Role</th>
                        <th class="px-3 py-2">Tanggal Dibuat</th>
                        <th class="px-3 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                    <tr class="odd:bg-gray-100 even:bg-white border-b">
                        <td class="px-3 py-2">
                            <input type="checkbox" name="ids[]" value="{{ $user->id }}" class="selectItem">
                        </td>
                        <td class="px-3 py-2">{{ $index + 1 }}</td>
                        <td class="px-3 py-2">{{ $user->name }}</td>
                        <td class="px-3 py-2">{{ $user->email }}</td>
                        <td class="px-3 py-2">
                            <span class="px-2 py-1 rounded text-xs
                                {{ $user->role == 'superadmin' ? 'bg-blue-600 text-white' : 'bg-gray-400 text-white' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-3 py-2">{{ $user->created_at->format('d M Y H:i') }}</td>
                        <td class="px-3 py-2 flex gap-2">
                            <button type="button" onclick='openEditModal(@json($user))'
                                class="px-3 py-1 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600">
                                Edit
                            </button>
                            <form action="{{ route('superadmin.destroy', $user->id) }}" method="POST"
                                class="deleteForm inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Belum ada data.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </form>
</div>

{{-- Modal --}}
<div id="studentModal"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-lg rounded shadow p-6">
        <form id="studentForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="flex justify-between items-center mb-4">
                <h5 class="text-lg font-bold text-gray-700" id="modalTitle">Tambah User</h5>
                <button type="button" onclick="closeModal()" class="text-gray-600 hover:text-gray-800">✕</button>
            </div>

            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                <div id="passwordField">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select name="sex" id="sex" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
                        <option value="">-- Pilih --</option>
                        <option value="male">Laki-laki</option>
                        <option value="female">Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Telepon</label>
                    <input type="text" name="telephone" id="telephone" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                    <input type="text" name="position" id="position" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Batal</button>
                <button type="submit" id="saveBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // BULK DELETE HANDLER
    const selectAll = document.getElementById('selectAll');
    const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');

    selectAll?.addEventListener('change', function() {
        document.querySelectorAll('.selectItem').forEach(cb => cb.checked = selectAll.checked);
        toggleDeleteBtn();
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('selectItem')) toggleDeleteBtn();
    });

    function toggleDeleteBtn() {
        deleteSelectedBtn.disabled = document.querySelectorAll('.selectItem:checked').length === 0;
    }

    // SWEETALERT CONFIRM DELETE
    document.querySelectorAll('.deleteForm').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!'
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    // BULK DELETE CONFIRM
    document.getElementById('bulkDeleteForm').addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Yakin ingin menghapus data terpilih?',
            text: "Data akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!'
        }).then(result => {
            if (result.isConfirmed) e.target.submit();
        });
    });

    // MODAL HANDLER
    function openCreateModal() {
        const form = document.getElementById('studentForm');
        form.reset();
        document.getElementById('formMethod').value = 'POST';
        form.action = "{{ route('superadmin.store') }}";
        document.getElementById('modalTitle').innerText = "Tambah User";
        document.getElementById('passwordField').classList.remove('hidden');
        document.getElementById('studentModal').classList.remove('hidden');
    }

    function openEditModal(user) {
        const form = document.getElementById('studentForm');
        form.reset();
        document.getElementById('formMethod').value = 'PUT';
        form.action = "/superadmin/" + user.id;
        document.getElementById('modalTitle').innerText = "Edit User";

        // otomatis isi semua field berdasarkan id
        ['name', 'email', 'sex', 'telephone', 'position'].forEach(key => {
            const el = document.getElementById(key);
            if (el && user[key] !== undefined) el.value = user[key];
        });

        document.getElementById('passwordField').classList.add('hidden'); // password tidak di-edit
        document.getElementById('studentModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('studentModal').classList.add('hidden');
    }
</script>
@endsection