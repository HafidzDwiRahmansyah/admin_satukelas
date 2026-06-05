@extends('layouts.app')

@section('content')
<div class="p-10">
    <h2 class="text-2xl font-bold mb-6 text-blue-700">User Belum Membayar</h2>

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
                    <td class="p-3">
                        <input type="checkbox" name="selected_users[]" value="{{ $user->id }}">
                    </td>
                    <td class="p-3">{{ $user->name }}</td>
                    <td class="p-3">{{ $user->position ?? '-' }}</td>
                    <td class="p-3">{{ $user->company ?? '-' }}</td>

                    <!-- Membership -->
                    <td class="p-3 max-w-xs">
                        <div class="max-h-24 overflow-y-auto space-y-1 border rounded p-2 bg-red-50">
                            @forelse($user->memberships as $membership)
                            @if($membership->expired_at < now())
                                <div class="border-b pb-1">
                                <strong>{{ $membership->paket->title ?? 'Paket #' . $membership->paket_membership_id }}</strong>
                                <br>
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
        <span class="px-2 py-1 text-xs rounded bg-red-200 text-red-600">Unpaid</span>
    </td>

    <td class="p-3">
        <div class="relative inline-block text-left">
            <button onclick="toggleMenu({{ $user->id }})" class="p-2 rounded hover:bg-gray-200">
                <i class="fas fa-ellipsis-v"></i>
            </button>
            <div id="menu-{{ $user->id }}" class="hidden absolute right-0 mt-2 w-40 bg-white border rounded shadow">
                <a href="{{ route('users.edit', $user->id) }}" class="block px-4 py-2 hover:bg-gray-100">Edit Profile</a>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Edit Membership</a>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Reset Password</a>
                <form method="POST" action="{{ route('users.destroy', $user->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600">Hapus Pengguna</button>
                </form>
            </div>
        </div>
    </td>
    </tr>
    @endforeach
    </tbody>
    </table>
</div>

<div class="mt-6 flex justify-end">
    <nav class="inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
        {{-- Previous Page Link --}}
        @if ($users->onFirstPage())
        <span class="px-3 py-2 ml-0 leading-tight text-gray-400 bg-white border border-gray-300 rounded-l-lg cursor-not-allowed">&laquo;</span>
        @else
        <a href="{{ $users->previousPageUrl() }}" class="px-3 py-2 ml-0 leading-tight text-gray-700 bg-white border border-gray-300 rounded-l-lg hover:bg-blue-100 hover:text-blue-700">&laquo;</a>
        @endif

        @php
        $total = $users->lastPage();
        $current = $users->currentPage();
        $side = 3; // jumlah angka kiri/kanan
        $adjacent = 1; // jumlah angka di sekitar halaman aktif
        @endphp

        {{-- Left Side --}}
        @for ($i = 1; $i <= min($side, $total); $i++)
            <a href="{{ $users->url($i) }}" class="px-3 py-2 leading-tight {{ $i == $current ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-blue-100 hover:text-blue-700' }} border border-gray-300">{{ $i }}</a>
            @endfor

            {{-- Ellipsis left --}}
            @if ($current - $adjacent > $side + 1)
            <span class="px-3 py-2 leading-tight text-gray-400 bg-white border border-gray-300">...</span>
            @endif

            {{-- Middle --}}
            @for ($i = max($side + 1, $current - $adjacent); $i <= min($current + $adjacent, $total - $side); $i++)
                <a href="{{ $users->url($i) }}" class="px-3 py-2 leading-tight {{ $i == $current ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-blue-100 hover:text-blue-700' }} border border-gray-300">{{ $i }}</a>
                @endfor

                {{-- Ellipsis right --}}
                @if ($current + $adjacent < $total - $side)
                    <span class="px-3 py-2 leading-tight text-gray-400 bg-white border border-gray-300">...</span>
                    @endif

                    {{-- Right Side --}}
                    @for ($i = max($total - $side + 1, $side + 1); $i <= $total; $i++)
                        <a href="{{ $users->url($i) }}" class="px-3 py-2 leading-tight {{ $i == $current ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-blue-100 hover:text-blue-700' }} border border-gray-300">{{ $i }}</a>
                        @endfor

                        {{-- Next Page Link --}}
                        @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="px-3 py-2 leading-tight text-gray-700 bg-white border border-gray-300 rounded-r-lg hover:bg-blue-100 hover:text-blue-700">&raquo;</a>
                        @else
                        <span class="px-3 py-2 leading-tight text-gray-400 bg-white border border-gray-300 rounded-r-lg cursor-not-allowed">&raquo;</span>
                        @endif
    </nav>
</div>
</div>

<script>
    function toggleMenu(id) {
        const menu = document.getElementById(`menu-${id}`);
        menu.classList.toggle('hidden');
        document.addEventListener('click', function handleClickOutside(event) {
            if (!menu.contains(event.target) && !event.target.closest(`#menu-${id}`)) {
                menu.classList.add('hidden');
                document.removeEventListener('click', handleClickOutside);
            }
        });
    }
</script>
@endsection