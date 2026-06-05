<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Dashboard')</title>

    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 28px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 190px;
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 20px;
        }

        .stat-label {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }
    </style>

    <!-- Tailwind CSS (CDN) -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Font Awesome (reliable CDN: jsDelivr) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.0/css/all.min.css">
</head>

<body class="bg-blue-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="w-64 bg-blue-600 text-white flex flex-col fixed inset-y-0 left-0 transform md:translate-x-0 -translate-x-full md:shadow-none shadow-xl md:rounded-none rounded-r-2xl transition-transform duration-300 z-50">

            <!-- Logo / Brand -->
            <div class="p-6 text-2xl font-semibold border-b border-blue-500 flex items-center gap-3">
                <span>SATUKELAS</span>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2">
                <!-- Home -->
                <a href="/dashboard" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-500 transition">
                    <i class="fas fa-home w-5"></i>
                    <span>Home</span>
                </a>

                <!-- Manajemen User Dropdown -->
                <div>
                    <button onclick="toggleUserDropdown()" class="w-full flex items-center justify-between gap-3 px-4 py-2 rounded-lg hover:bg-blue-500 transition">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-users w-5"></i>
                            <span>Manajemen User</span>
                        </span>
                        <i id="userArrow" class="fas fa-chevron-down transition-transform"></i>
                    </button>

                    <div id="userDropdown" class="hidden pl-10 mt-2 space-y-2">
                        <a href="/students/paid" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
                            <i class="fas fa-check-circle w-4"></i>
                            <span>Sudah Bayar</span>
                        </a>
                        <a href="/students/unpaid" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
                            <i class="fas fa-times-circle w-4"></i>
                            <span>Belum Bayar</span>
                        </a>
                    </div>
                </div>

                <!-- Manajemen Admin -->
                <a href="/superadmin/" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-500 transition">
                    <i class="fas fa-user-shield w-5"></i>
                    <span>Manajemen Admin</span>
                </a>

                <!-- Manajemen Assets -->
                <a href="/assets/" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-500 transition">
                    <i class="fas fa-image w-5"></i>
                    <span>Manajemen Assets</span>
                </a>
                
                <!-- Manajemen Assets -->
                <a href="/active-users" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-500 transition">
                    <i class="fas fa-image w-5"></i>
                    <span>Active User</span>
                </a>

                <!-- Manajemen Pelatihan Dropdown -->
                <div>
                    <button onclick="togglePelatihanDropdown()" class="w-full flex items-center justify-between gap-3 px-4 py-2 rounded-lg hover:bg-blue-500 transition">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-book-open w-5"></i>
                            <span>Manajemen Pelatihan</span>
                        </span>
                        <i id="pelatihanArrow" class="fas fa-chevron-down transition-transform"></i>
                    </button>

                    <div id="pelatihanDropdown" class="hidden pl-10 mt-2 space-y-2">
                        <a href="/learning_paths/" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
                            <i class="fas fa-layer-group w-4"></i>
                            <span>Learning Path</span>
                        </a>
                        <a href="/courses/" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
                            <i class="fas fa-graduation-cap w-4"></i>
                            <span>Course</span>
                        </a>
                        <!-- <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
                            <i class="fas fa-book w-4"></i>
                            <span>Lessons</span>
                        </a> -->
                        <a href="/webinars/" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
                            <i class="fas fa-video w-4"></i>
                            <span>Webinar</span>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Logout -->
            <div class="p-4 border-t border-blue-500">
                <!-- Tombol Logout -->
                <a href="#"
                    onclick="confirmLogout(event)"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-500 transition">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Content area -->
        <div class="flex-1 flex flex-col md:ml-64">
            <!-- Topbar for mobile -->
            <header class="md:hidden bg-white p-4 shadow">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-blue-700">SATUKELAS</span>
                    </div>
                    <button onclick="toggleMobileSidebar()" class="p-2 bg-blue-500 text-white rounded">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </header>

            <main class="flex-1 overflow-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Form logout tersembunyi -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    <!-- Tambahkan SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmLogout(e) {
            e.preventDefault(); // cegah langsung redirect

            Swal.fire({
                title: 'Yakin ingin keluar?',
                text: "Anda akan keluar dari akun ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2563eb', // biru
                cancelButtonColor: '#d33', // merah
                confirmButtonText: 'Ya, keluar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>

    <script>
        function toggleUserDropdown() {
            const d = document.getElementById('userDropdown');
            const a = document.getElementById('userArrow');
            d.classList.toggle('hidden');
            a.classList.toggle('rotate-180');
        }

        function togglePelatihanDropdown() {
            const d = document.getElementById('pelatihanDropdown');
            const a = document.getElementById('pelatihanArrow');
            d.classList.toggle('hidden');
            a.classList.toggle('rotate-180');
        }

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        }

        window.addEventListener('resize', () => {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('-translate-x-full');
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>
</body>

</html>