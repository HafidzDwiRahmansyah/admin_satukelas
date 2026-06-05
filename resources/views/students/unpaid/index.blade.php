@extends('layouts.app')

@section('content')
<div class="px-6 py-6">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-700">Students Unpaid</h2>
        <button onclick="openCreateModal()"
            class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg shadow hover:from-green-600 hover:to-green-700 flex items-center gap-2">
            <span class="text-lg font-bold">+</span> Tambah Student
        </button>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full text-left border border-gray-200">
            <thead class="bg-gray-900 text-white">
                <tr>
                    <th class="px-3 py-2">#</th>
                    <th class="px-3 py-2">Nama</th>
                    <th class="px-3 py-2">Email</th>
                    <th class="px-3 py-2">Membership</th>
                    <th class="px-3 py-2">Role</th>
                    <th class="px-3 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $index => $student)
                <tr class="odd:bg-gray-50 even:bg-white hover:bg-gray-100 border-b">
                    <td class="px-3 py-2">{{ $index + $students->firstItem() }}</td>
                    <td class="px-3 py-2 font-medium">{{ $student->name }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $student->email }}</td>
                    <td class="px-3 py-2 flex flex-wrap gap-1">
                        @forelse ($student->memberships as $membership)
                        <span class="px-2 py-1 rounded-full text-xs {{ $membership->expired_at < now() ? 'bg-gray-400 text-gray-700' : 'bg-green-600 text-white' }}">
                            {{ $membership->paket->title ?? '-' }}
                            ({{ \Carbon\Carbon::parse($membership->start_at)->format('d M Y') }}
                            - {{ \Carbon\Carbon::parse($membership->expired_at)->format('d M Y') }})
                        </span>
                        @empty
                        <span class="px-2 py-1 rounded-full text-xs bg-gray-300 text-gray-700">Unpaid</span>
                        @endforelse
                    </td>
                    <td class="px-3 py-2">{{ ucfirst($student->role) }}</td>
                    <td class="px-3 py-2 flex gap-2">
                        <button type="button" onclick='openEditModal(@json($student))'
                            class="px-3 py-1 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600 shadow-sm flex items-center gap-1">
                            ✏️ Edit
                        </button>
                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="deleteForm inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 shadow-sm flex items-center gap-1">
                                🗑 Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-gray-500">Belum ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $students->links() }}
    </div>
</div>

{{-- Modal Form (Create & Edit) --}}
<div id="studentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6 max-h-[90vh] overflow-y-auto">
        <form id="studentForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="flex justify-between items-center mb-4">
                <h5 class="text-lg font-bold text-gray-700" id="modalTitle">Tambah Student</h5>
                <button type="button" onclick="closeModal()" class="text-gray-600 hover:text-gray-800 text-xl">✕</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="name" id="name"
                        class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email"
                        class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                {{-- Password --}}
                <div id="passwordField">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                {{-- Sex --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select name="sex" id="sex"
                        class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                        <option value="">-- Pilih --</option>
                        <option value="male">Laki-laki</option>
                        <option value="female">Perempuan</option>
                    </select>
                </div>

                {{-- Telephone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Telepon</label>
                    <input type="text" name="telephone" id="telephone"
                        class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                {{-- Position --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                    <input type="text" name="position" id="position"
                        class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                {{-- Membership --}}
                <div id="membershipField" class="md:col-span-2 hidden">
                    <label class="block text-sm font-medium text-gray-700">Paket Membership</label>
                    <div class="flex gap-2 items-center">
                        <select name="paket_membership_id[0][paket_membership_id]" id="paket_membership_id"
                            class="w-1/2 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                            <option value="">-- Pilih Paket --</option>
                            @foreach(\App\Models\PaketMembership::all() as $paket)
                            <option value="{{ $paket->id }}">{{ $paket->title }}</option>
                            @endforeach
                        </select>

                        <input type="date" name="paket_membership_id[0][start_date]"
                            class="w-1/4 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" placeholder="Start Date">

                        <input type="date" name="paket_membership_id[0][end_date]"
                            class="w-1/4 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" placeholder="End Date">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Batal</button>
                <button type="submit" id="saveBtn"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function openCreateModal() {
        document.getElementById('studentForm').reset();
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('studentForm').action = "{{ route('students.store') }}";
        document.getElementById('modalTitle').innerText = "Tambah Student";
        document.getElementById('passwordField').classList.remove('hidden'); // tampilkan password
        document.getElementById('membershipField').classList.add('hidden'); // sembunyikan membership
        document.getElementById('studentModal').classList.remove('hidden');
    }

    function openEditModal(student) {
        document.getElementById('studentForm').reset();
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('studentForm').action = "/students/" + student.id;
        document.getElementById('modalTitle').innerText = "Edit Student";

        // isi data
        document.getElementById('name').value = student.name;
        document.getElementById('email').value = student.email;
        document.getElementById('sex').value = student.sex;
        document.getElementById('telephone').value = student.phone;
        document.getElementById('position').value = student.position;

        document.getElementById('passwordField').classList.add('hidden'); // sembunyikan password
        document.getElementById('membershipField').classList.remove('hidden'); // tampilkan membership

        // set membership jika ada
        if (student.memberships && student.memberships.length > 0) {
            const membership = student.memberships[0];
            document.getElementById('paket_membership_id').value = membership.paket_membership_id;
            document.querySelector('input[name="paket_membership_id[0][start_date]"]').value = membership.start_at.split(' ')[0];
            document.querySelector('input[name="paket_membership_id[0][end_date]"]').value = membership.expired_at.split(' ')[0];
        }

        document.getElementById('studentModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('studentModal').classList.add('hidden');
    }

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
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>
@endsection