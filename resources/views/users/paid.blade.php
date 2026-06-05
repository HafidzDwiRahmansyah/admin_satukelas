@extends('layouts.app')

@section('content')
<div class="p-10">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-700">Manajemen User</h2>
        <button onclick="openUserModal()"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
            + Tambah User
        </button>
    </div>

    {{-- Table User --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full text-sm">
            <thead class="bg-blue-100">
                <tr>
                    <th class="p-3"><input type="checkbox"></th>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Position</th>
                    <th class="p-3 text-left">Company</th>
                    <th class="p-3 text-left">Membership</th>
                    <th class="p-3 text-left">Tanggal Terdaftar</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Menu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-b hover:bg-blue-50 align-top">
                    <td class="p-3"><input type="checkbox" value="{{ $user->id }}"></td>
                    <td class="p-3">{{ $user->name }}</td>
                    <td class="p-3">{{ $user->position ?? '-' }}</td>
                    <td class="p-3">{{ $user->company ?? '-' }}</td>

                    {{-- Membership --}}
                    <td class="p-3 max-w-xs">
                        <div class="max-h-24 overflow-y-auto space-y-1 border rounded p-2 bg-blue-50">
                            @forelse($user->memberships as $membership)
                            @if($membership->expired_at >= now())
                            <div class="border-b pb-1">
                                <strong>{{ $membership->paket->title ?? 'Paket #' . $membership->paket_membership_id }}</strong><br>
                                <small>Exp: {{ \Carbon\Carbon::parse($membership->expired_at)->format('Y-m-d') }}</small>
                            </div>
                            @endif
                            @empty
                            <span class="text-gray-400">-</span>
                            @endforelse
                        </div>
                    </td>

                    <td class="p-3">{{ $user->created_at->format('Y-m-d') }}</td>
                    <td class="p-3">
                        @if($user->memberships->where('expired_at', '>=', now())->count() > 0)
                        <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Aktif</span>
                        @else
                        <span class="px-2 py-1 text-xs rounded bg-gray-200 text-gray-600">Nonaktif</span>
                        @endif
                    </td>
                    <td class="p-3">
                        <button onclick="openUserModal({{ $user }})" class="text-blue-600 hover:underline">Edit</button>
                        <form method="POST" action="{{ route('users.destroy',$user->id) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline ml-2">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-6">{{ $users->links() }}</div>
</div>

{{-- Popup Modal --}}
<div id="userModal" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white w-3/4 rounded-lg shadow-lg p-6">
        <h3 id="modalTitle" class="text-xl font-bold mb-4">Tambah User</h3>

        <form id="userForm" method="POST" action="{{ route('users.store') }}">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="grid grid-cols-2 gap-6">
                {{-- Kiri --}}
                <div class="space-y-4">
                    <div>
                        <label class="block font-medium">Nama</label>
                        <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block font-medium">Email</label>
                        <input type="email" name="email" id="email" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block font-medium">Password</label>
                        <input type="password" name="password" id="password" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block font-medium">Position</label>
                        <input type="text" name="position" id="position" class="w-full border rounded px-3 py-2">
                    </div>
                </div>

                {{-- Kanan --}}
                <div class="space-y-4">
                    <div>
                        <label class="block font-medium">Company</label>
                        <input type="text" name="company" id="company" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block font-medium">Role</label>
                        <select name="role" id="role" class="w-full border rounded px-3 py-2">
                            <option value="student">Student</option>
                            <option value="admin">Admin</option>
                            <option value="superadmin">Superadmin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-medium">Membership</label>
                        <select name="paket_membership_id[]" id="paket_membership_id" multiple class="w-full border rounded px-3 py-2">
                            @foreach($paketMemberships as $paket)
                            <option value="{{ $paket->id }}">{{ $paket->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-6 space-x-3">
                <button type="button" onclick="closeUserModal()" class="px-4 py-2 border rounded">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openUserModal(user = null) {
        const modal = document.getElementById("userModal");
        const form = document.getElementById("userForm");
        const method = document.getElementById("formMethod");
        const title = document.getElementById("modalTitle");

        modal.classList.remove("hidden");

        if (user) {
            // EDIT
            form.action = `/users/${user.id}`;
            method.value = "PUT";
            title.innerText = "Edit User";

            document.getElementById("name").value = user.name;
            document.getElementById("email").value = user.email;
            document.getElementById("position").value = user.position ?? '';
            document.getElementById("company").value = user.company ?? '';
            document.getElementById("role").value = user.role;
        } else {
            // CREATE
            form.action = `{{ route('users.store') }}`;
            method.value = "POST";
            title.innerText = "Tambah User";
            form.reset();
        }
    }

    function closeUserModal() {
        document.getElementById("userModal").classList.add("hidden");
    }
</script>
@endsection