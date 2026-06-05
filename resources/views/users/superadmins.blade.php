@extends('layouts.app')

@section('content')
<div class="p-10">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-700">Manajemen Superadmin</h2>
        <button onclick="openAddUserModal()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Tambah User</button>

        <!-- Modal Tambah/Edit User -->
        <div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 relative">
                <button onclick="closeAddUserModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
                <h3 class="text-xl font-bold mb-4" id="addUserModalTitle">Tambah User</h3>
                <form id="addUserForm" method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Name</label>
                        <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Sex</label>
                        <select name="sex" class="w-full border rounded px-3 py-2" required>
                            <option value="">-- Pilih Sex --</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Telephone</label>
                        <input type="text" name="telephone" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Type User</label>
                        <select name="type_user" class="w-full border rounded px-3 py-2" required>
                            <option value="">-- Pilih Type User --</option>
                            <option value="superadmin">Superadmin</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Position</label>
                        <input type="text" name="position" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Email</label>
                        <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Password</label>
                        <input type="password" name="password" class="w-full border rounded px-3 py-2">
                        <small id="passwordHelp" class="text-gray-500">Kosongkan jika tidak ingin mengganti password saat edit.</small>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeAddUserModal()" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Superadmin -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full text-sm">
            <thead class="bg-blue-100">
                <tr>
                    <th class="p-3"><input type="checkbox"></th>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Type User</th>
                    <th class="p-3 text-left">Tanggal Terdaftar</th>
                    <th class="p-3 text-left">Menu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($superadmins as $user)
                <tr class="border-b hover:bg-blue-50 align-top">
                    <td class="p-3">
                        <input type="checkbox" name="selected_users[]" value="{{ $user->id }}">
                    </td>
                    <td class="p-3">{{ $user->name }}</td>
                    <td class="p-3">{{ $user->role }}</td>
                    <td class="p-3">{{ $user->created_at->format('Y-m-d') }}</td>
                    <td class="p-3">
                        <button
                            onclick="openAddUserModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->sex }}', '{{ $user->telephone }}', '{{ $user->type_user }}', '{{ $user->position }}')"
                            class="p-2 rounded hover:bg-gray-200">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button onclick="confirmDelete({{ $user->id }})" class="p-2 rounded hover:bg-red-100 text-red-600">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-end">
        {{ $superadmins->links() }}
    </div>
</div>

<script>
    function openAddUserModal(id = null, name = '', email = '', sex = '', telephone = '', type_user = '', position = '') {
    const modal = document.getElementById('addUserModal');
    const form = document.getElementById('addUserForm');
    const title = document.getElementById('addUserModalTitle');

    if (id) {
        // Mode edit
        title.innerText = 'Edit User';
        form.action = `/users/${id}`;

        // Tambahkan hidden input _method jika belum ada
        if (!form.querySelector('input[name="_method"]')) {
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);
        }

        // Isi input form
        form.querySelector('input[name="name"]').value = name;
        form.querySelector('input[name="email"]').value = email;
        form.querySelector('select[name="sex"]').value = sex;
        form.querySelector('input[name="telephone"]').value = telephone;
        form.querySelector('select[name="type_user"]').value = type_user;
        form.querySelector('input[name="position"]').value = position;
        form.querySelector('input[name="password"]').value = '';
    } else {
        // Mode tambah
        title.innerText = 'Tambah User';
        form.action = "{{ route('users.store') }}";
        form.querySelector('input[name="_method]')?.remove(); // hapus method PUT jika ada
        form.reset();
    }

    modal.classList.remove('hidden');
}

    function closeAddUserModal() {
        document.getElementById('addUserModal').classList.add('hidden');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data user akan dihapus permanen!",
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
                form.action = `/users/${id}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection