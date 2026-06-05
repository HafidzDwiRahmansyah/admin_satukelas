@extends('layouts.app')

@section('content')
<div class="p-10">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-700">Courses</h2>
        <button onclick="openAddModal()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Tambah Course</button>
    </div>

    <form action="{{ route('courses.index') }}" method="GET" class="flex flex-col md:flex-row gap-2 mb-6">
        {{-- Dropdown pilih filter --}}
        <select name="filter" class="border rounded px-3 py-2">
            <option value="">-- Cari berdasarkan --</option>
            <option value="title" {{ request('filter') == 'title' ? 'selected' : '' }}>Title</option>
            <option value="learning_path" {{ request('filter') == 'learning_path' ? 'selected' : '' }}>Learning Path</option>
            <option value="course_type" {{ request('filter') == 'course_type' ? 'selected' : '' }}>Course Type</option>
            <option value="instructor" {{ request('filter') == 'instructor' ? 'selected' : '' }}>Instructor</option>
        </select>

        {{-- Input keyword --}}
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Masukkan kata kunci..."
            class="w-full border rounded px-3 py-2">

        {{-- Tombol Search --}}
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Search
        </button>
    </form>

    <a href="{{ route('courses.export') }}"
    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
    Export Excel
    </a>

    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full text-sm">
            <thead class="bg-blue-100">
                <tr>
                    <th class="p-3">Learning Path</th>
                    <th class="p-3">Course Type</th>
                    <th class="p-3">Title</th>
                    <th class="p-3">Thumbnail</th>
                    <th class="p-3">Price</th>
                    <th class="p-3">Instructor</th>
                    <th class="p-3">Favorit</th>
                    <th class="p-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $c)
                <tr class="border-b hover:bg-blue-50">
                    <td class="p-3">{{ $c->learningPath->title ?? '-' }}</td>
                    <td class="p-3">{{ $c->courseType->title ?? '-' }}</td>
                    <td class="p-3">{{ $c->title }}</td>
                    <td class="p-3">
                        @if($c->thumbnail)
                        <img src="{{ $c->thumbnail }}" class="h-10 w-10 object-cover rounded">
                        @else
                        -
                        @endif
                    </td>
                    <td class="p-3">Rp {{ number_format($c->price, 0, ',', '.') }}</td>
                    <td class="p-3">{{ $c->instructor->name ?? '-' }}</td>
                    <td class="p-3">{{ $c->is_favorit ? '✅' : '❌' }}</td>
                    <td class="p-3 space-x-2">
                        <button onclick="openEditModal({{ $c->id }}, {{ $c->learning_path_id }}, {{ $c->course_type_id }}, '{{ $c->title }}', '{{ $c->thumbnail }}', '{{ $c->price }}', '{{ $c->description }}', {{ $c->instructor_id }}, '{{ $c->objective }}', {{ $c->is_favorit ? 1 : 0 }})"
                            class="px-2 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500">Edit</button>

                        <button onclick="confirmDelete({{ $c->id }})"
                            class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah/Edit -->
    <div id="courseModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative">
            <button onclick="closeCourseModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
            <h3 id="modalTitle" class="text-xl font-bold mb-4">Tambah Course</h3>
            <form id="courseForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="mb-3">
                    <label class="block mb-1 font-medium">Learning Path</label>
                    <select name="learning_path_id" id="cLearningPath" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Pilih --</option>
                        @foreach($learningPaths as $lp)
                        <option value="{{ $lp->id }}">{{ $lp->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block mb-1 font-medium">Course Type</label>
                    <select name="course_type_id" id="cCourseType" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Pilih --</option>
                        @foreach($courseTypes as $ct)
                        <option value="{{ $ct->id }}">{{ $ct->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block mb-1 font-medium">Title</label>
                    <input type="text" name="title" id="cTitle" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-3">
                    <label class="block mb-1 font-medium">Thumbnail URL</label>
                    <input type="text" name="thumbnail" id="cThumbnail" class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-3">
                    <label class="block mb-1 font-medium">Price</label>
                    <input type="text" id="cPrice" class="w-full border rounded px-3 py-2" placeholder="Rp 0">
                    <input type="hidden" name="price" id="cPriceHidden" value="0">
                </div>

                <div class="mb-3">
                    <label class="block mb-1 font-medium">Description</label>
                    <textarea name="description" id="cDescription" class="w-full border rounded px-3 py-2"></textarea>
                </div>

                <div class="mb-3">
                    <label class="block mb-1 font-medium">Instructor</label>
                    <select name="instructor_id" id="cInstructor" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Pilih --</option>
                        @foreach($instructors as $ins)
                        <option value="{{ $ins->id }}">{{ $ins->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block mb-1 font-medium">Objective</label>
                    <textarea name="objective" id="cObjective" class="w-full border rounded px-3 py-2"></textarea>
                </div>

                <div class="mb-3">
                    <label class="block mb-1 font-medium">Favorit</label>
                    <select name="is_favorit" id="cFavorit" class="w-full border rounded px-3 py-2">
                        <option value="0">Tidak</option>
                        <option value="1">Ya</option>
                    </select>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-6">
        {{ $courses->links() }}
    </div>
</div>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let query = this.value;

        fetch(`/courses/search?q=${query}`)
            .then(response => response.json())
            .then(data => {
                let tbody = document.getElementById('courseTableBody');
                tbody.innerHTML = '';

                if (data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="7" class="text-center p-3">Tidak ada data ditemukan</td></tr>`;
                    return;
                }

                data.forEach(c => {
                    tbody.innerHTML += `
                    <tr class="border-b hover:bg-blue-50">
                        <td class="p-3">${c.learning_path?.title ?? '-'}</td>
                        <td class="p-3">${c.course_type?.title ?? '-'}</td>
                        <td class="p-3">${c.title}</td>
                        <td class="p-3">
                            ${c.thumbnail ? `<img src="${c.thumbnail}" class="h-10 w-10 object-cover rounded">` : '-'}
                        </td>
                        <td class="p-3">Rp ${new Intl.NumberFormat('id-ID').format(c.price)}</td>
                        <td class="p-3">${c.instructor?.name ?? '-'}</td>
                        <td class="p-3">${c.is_favorit ? '✅' : '❌'}</td>
                    </tr>
                `;
                });
            });
    });

    const cPriceInput = document.getElementById('cPrice');
    const cPriceHidden = document.getElementById('cPriceHidden');

    function formatRupiah(angka) {
        let number_string = angka.replace(/[^,\d]/g, "").toString(),
            split = number_string.split(","),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] !== undefined ? rupiah + "," + split[1] : rupiah;
        return rupiah ? "Rp " + rupiah : "";
    }

    cPriceInput.addEventListener("keyup", function(e) {
        this.value = formatRupiah(this.value);
        cPriceHidden.value = this.value.replace(/[^0-9]/g, ""); // simpan angka murni
    });

    function openAddModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Course';
        const form = document.getElementById('courseForm');
        form.action = "{{ route('courses.store') }}";
        document.getElementById('formMethod').value = "POST";
        form.reset();
        document.getElementById('courseModal').classList.remove('hidden');
    }

    function openEditModal(id, lp_id, ct_id, title, thumbnail, price, description, instructor_id, objective, is_favorit) {
        document.getElementById('modalTitle').innerText = 'Edit Course';
        const form = document.getElementById('courseForm');
        form.action = `/courses/${id}`;
        document.getElementById('formMethod').value = "PUT";

        document.getElementById('cLearningPath').value = lp_id;
        document.getElementById('cCourseType').value = ct_id;
        document.getElementById('cTitle').value = title;
        document.getElementById('cThumbnail').value = thumbnail;
        document.getElementById('cPrice').value = price;
        document.getElementById('cDescription').value = description;
        document.getElementById('cInstructor').value = instructor_id;
        document.getElementById('cObjective').value = objective;
        document.getElementById('cFavorit').value = is_favorit;

        document.getElementById('courseModal').classList.remove('hidden');
    }

    function closeCourseModal() {
        document.getElementById('courseModal').classList.add('hidden');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data Course akan dihapus permanen!",
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
                form.action = `/courses/${id}`;
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