@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <h2 class="text-3xl font-bold mb-6 text-blue-700">📂 Assets Management</h2>

    {{-- Alert sukses --}}
    @if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('
                success ') }}',
                timer: 2000,
                showConfirmButton: false
            });
        });
    </script>
    @endif

    {{-- Upload Form --}}
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-4">
            @csrf
            <input type="file" name="file" required class="border p-2 rounded w-full">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Upload</button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Preview</th>
                    <th class="px-4 py-2 text-left">File Name</th>
                    <th class="px-4 py-2 text-left">URL</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                    <th class="px-4 py-2 text-left">Copy URL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assets as $asset)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $asset->id }}</td>
                    <td class="px-4 py-2">
                        <img src="{{ $asset->url }}" alt="" class="w-20 h-20 object-cover rounded border">
                    </td>
                    <td class="px-4 py-2">
                        <form action="{{ route('assets.update',$asset->id) }}" method="POST" class="flex items-center gap-2">
                            @csrf @method('PUT')
                            <input type="text" name="file_name" value="{{ $asset->file_name }}" class="border rounded p-1 w-40">
                            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">Save</button>
                        </form>
                    </td>
                    <td class="px-4 py-2 text-blue-600 underline">
                        <a href="{{ $asset->url }}" target="_blank">{{ $asset->url }}</a>
                    </td>
                    <td class="px-4 py-2">
                        <form action="{{ route('assets.destroy',$asset->id) }}" method="POST" class="inline delete-form">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                        </form>
                    </td>
                    <td class="px-4 py-2">
                        <button onclick="copyToClipboard('{{ $asset->url }}')"
                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Copy URL
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $assets->links() }}
    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('URL copied to clipboard!');
        }, function(err) {
            alert('Failed to copy: ', err);
        });
    }

    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin hapus?',
                text: "File akan dihapus permanen dari storage!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection