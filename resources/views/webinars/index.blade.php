@extends('layouts.app')

@section('content')
<div class="p-10">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-700">Manajemen Webinar</h2>
        <button onclick="openFormModal()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Tambah Webinar</button>
    </div>

    {{-- Tabel Webinar --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full text-sm">
            <thead class="bg-blue-100">
                <tr>
                    <th class="p-3">Course</th>
                    <th class="p-3">Published</th>
                    <th class="p-3">Total Seats</th>
                    <th class="p-3">Start</th>
                    <th class="p-3">Ends</th>
                    <th class="p-3">Meeting Link</th>
                    <th class="p-3">Passcode</th>
                    <th class="p-3">Menu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($webinars as $webinar)
                <tr class="border-b hover:bg-blue-50">
                    <td class="p-3">{{ $webinar->course->title ?? '-' }}</td>
                    <td class="p-3">{{ $webinar->is_published ? 'Yes' : 'No' }}</td>
                    <td class="p-3">{{ $webinar->total_seats }}</td>
                    <td class="p-3">{{ $webinar->time_starts }}</td>
                    <td class="p-3">{{ $webinar->time_ends }}</td>
                    <td class="p-3">
                        @if($webinar->meeting_link)
                        <a href="{{ $webinar->meeting_link }}" target="_blank" class="text-blue-600 hover:underline">Link</a>
                        @else
                        -
                        @endif
                    </td>
                    <td class="p-3">{{ $webinar->passcode ?? '-' }}</td>
                    <td class="p-3 space-x-2">
                        <button onclick="openFormModal({{ $webinar->id }}, '{{ $webinar->course_id }}', '{{ $webinar->total_seats }}', '{{ $webinar->time_starts }}', '{{ $webinar->time_ends }}', '{{ $webinar->meeting_link }}', '{{ $webinar->passcode }}', {{ $webinar->is_published ? 1 : 0 }}, '{{ $webinar->course->title ?? '' }}')"
                            class="px-2 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500">Edit</button>

                        <form method="POST" action="{{ route('webinars.destroy', $webinar->id) }}" class="inline-block delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 delete-btn">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $webinars->links() }}
    </div>
</div>

{{-- Modal Form Create/Edit --}}
<div id="formModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
        <h2 id="formTitle" class="text-xl font-bold mb-4">Tambah Webinar</h2>
        <form id="webinarForm" method="POST" action="{{ route('webinars.store') }}">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="id" id="webinarId">

            <div class="mb-3">
                <label class="block mb-1">Course</label>
                <select name="course_id" id="courseId" style="width: 100%"></select>
            </div>

            <div class="mb-3">
                <label class="block mb-1">Total Seats</label>
                <input type="number" name="total_seats" id="totalSeats" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="block mb-1">Start Time</label>
                <input type="datetime-local" name="time_starts" id="timeStarts" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="block mb-1">End Time</label>
                <input type="datetime-local" name="time_ends" id="timeEnds" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="block mb-1">Meeting Link</label>
                <input type="text" name="meeting_link" id="meetingLink" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="block mb-1">Passcode</label>
                <input type="text" name="passcode" id="passcode" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="block mb-1">Published</label>
                <select name="is_published" id="isPublished" class="w-full border rounded px-3 py-2">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeFormModal()" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Select2 assets --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.delete-btn').click(function(e) {
            e.preventDefault();
            var form = $(this).closest('form');

            Swal.fire({
                title: 'Yakin ingin menghapus data ini?',
                text: "Data akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Hapus!',
                cancelButtonText: 'No, Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // submit form jika Yes
                }
            });
        });
    });

    function openFormModal(id = null, course_id = '', total_seats = '', time_starts = '', time_ends = '', meeting_link = '', passcode = '', is_published = 0, course_title = '') {
        $('#formModal').removeClass('hidden');

        $('#courseId').select2({
            placeholder: 'Ketik untuk mencari course...',
            allowClear: true,
            dropdownParent: $('#formModal'),
            ajax: {
                url: '{{ route("courses.search") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term // keyword user
                    };
                },
                processResults: function(data) {
                    return {
                        results: data // id dan text sudah sesuai
                    };
                },
                cache: true
            },
            minimumInputLength: 1,
            width: 'resolve'
        });

        if (id) {
            $('#formTitle').text('Edit Webinar');
            $('#webinarForm').attr('action', '/webinars/' + id);
            $('#formMethod').val('PUT');

            $('#webinarId').val(id);
            $('#totalSeats').val(total_seats);
            $('#timeStarts').val(time_starts);
            $('#timeEnds').val(time_ends);
            $('#meetingLink').val(meeting_link);
            $('#passcode').val(passcode);
            $('#isPublished').val(is_published);

            if (course_id) {
                var option = new Option(course_title, course_id, true, true);
                $('#courseId').append(option).trigger('change');
            }
        } else {
            $('#formTitle').text('Tambah Webinar');
            $('#webinarForm').attr('action', '{{ route("webinars.store") }}');
            $('#formMethod').val('POST');
            $('#webinarForm')[0].reset();
            $('#courseId').val(null).trigger('change');
        }
    }

    function closeFormModal() {
        $('#formModal').addClass('hidden');
    }
</script>
@endsection