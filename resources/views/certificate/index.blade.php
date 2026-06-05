@extends('layouts.app')

@section('content')
<div class="p-10">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-700">Certificates</h2>
    </div>

    <form action="{{ route('certificate.index') }}" method="GET" class="flex flex-col md:flex-row gap-2 mb-6">

        <input type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari User ID atau Course..."
            class="w-full border rounded px-3 py-2">

        <button type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Search
        </button>

    </form>

    <div class="overflow-x-auto bg-white rounded-lg shadow-md">

        <table class="min-w-full text-sm">

            <thead class="bg-blue-100">
                <tr>
                    <th class="p-3 text-left">User</th>
                    <th class="p-3 text-left">Course</th>
                    <th class="p-3 text-left">Created</th>
                </tr>
            </thead>

            <tbody>

                @forelse($certificates as $c)

                <tr class="border-b hover:bg-blue-50">

                    <td class="p-3">
                        {{ $c->user->name ?? '-' }}
                        <div class="text-xs text-gray-500">
                            ID: {{ $c->user_id }}
                        </div>
                    </td>

                    <td class="p-3">
                        {{ $c->course->title ?? '-' }}
                    </td>

                    <td class="p-3 text-gray-500">
                        {{ $c->created_at ? $c->created_at->format('d M Y H:i') : '-' }}
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="3" class="text-center p-6 text-gray-500">
                        Tidak ada data certificate
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-6">
        {{ $certificates->links() }}
    </div>

</div>
@endsection