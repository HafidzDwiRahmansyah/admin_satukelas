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

        .sidebar-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(191, 219, 254, 0.65) transparent;
        }

        .sidebar-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(191, 219, 254, 0.65);
            border-radius: 9999px;
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
    @php
        $isUserMenuOpen = request()->is('students/*');
        $isTrainingMenuOpen = request()->routeIs(
            'learning_paths.*',
            'courses.*',
            'lessons.*',
            'topics.*',
            'webinars.*',
            'certificate.*'
        );
        $isFlowOpen = request()->routeIs('flow.*');
        $isAuditOpen = request()->routeIs('data-audit.*');
        $activeMenuClass = 'bg-blue-500 shadow-md font-semibold';
    @endphp

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="w-64 h-screen shrink-0 bg-blue-600 text-white flex flex-col fixed inset-y-0 left-0 transform md:translate-x-0 -translate-x-full md:shadow-none shadow-xl md:rounded-none rounded-r-2xl transition-transform duration-300 z-50">

            <!-- Logo / Brand -->
            <div class="p-5 shrink-0 text-2xl font-semibold border-b border-blue-500 flex items-center justify-between gap-3">
                <span>SATUKELAS</span>
                <button type="button" onclick="closeMobileSidebar()"
                    class="md:hidden w-9 h-9 rounded-lg hover:bg-blue-500 transition"
                    aria-label="Tutup menu">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 min-h-0 overflow-y-auto sidebar-scrollbar p-4 space-y-2">
                <!-- Home -->
                <a href="{{ url('/dashboard') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->is('dashboard') ? $activeMenuClass : '' }}"
                    aria-current="{{ request()->is('dashboard') ? 'page' : 'false' }}">
                    <i class="fas fa-home w-5"></i>
                    <span>Home</span>
                </a>

                <!-- System Flow -->
                <a href="{{ route('flow.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-500 transition {{ $isFlowOpen ? $activeMenuClass : '' }}"
                    aria-current="{{ $isFlowOpen ? 'page' : 'false' }}">
                    <i class="fas fa-diagram-project w-5"></i>
                    <span>Alur Sistem</span>
                </a>

                <!-- Data Audit -->
                <a href="{{ route('data-audit.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-500 transition {{ $isAuditOpen ? $activeMenuClass : '' }}"
                    aria-current="{{ $isAuditOpen ? 'page' : 'false' }}">
                    <i class="fas fa-shield-halved w-5"></i>
                    <span>Audit Data</span>
                </a>

                <!-- Manajemen User Dropdown -->
                <div>
                    <button type="button" onclick="toggleUserDropdown()"
                        class="w-full flex items-center justify-between gap-3 px-4 py-2 rounded-lg hover:bg-blue-500 transition {{ $isUserMenuOpen ? 'bg-blue-500 shadow-md' : '' }}"
                        aria-controls="userDropdown" aria-expanded="{{ $isUserMenuOpen ? 'true' : 'false' }}">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-users w-5"></i>
                            <span>Manajemen User</span>
                        </span>
                        <i id="userArrow" class="fas fa-chevron-down transition-transform {{ $isUserMenuOpen ? 'rotate-180' : '' }}"></i>
                    </button>

                    <div id="userDropdown" class="{{ $isUserMenuOpen ? '' : 'hidden' }} pl-10 mt-2 space-y-2">
                        <a href="{{ url('/students/paid') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->is('students/paid') ? $activeMenuClass : '' }}"
                            aria-current="{{ request()->is('students/paid') ? 'page' : 'false' }}">
                            <i class="fas fa-check-circle w-4"></i>
                            <span>Sudah Bayar</span>
                        </a>
                        <a href="{{ url('/students/unpaid') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->is('students/unpaid') ? $activeMenuClass : '' }}"
                            aria-current="{{ request()->is('students/unpaid') ? 'page' : 'false' }}">
                            <i class="fas fa-times-circle w-4"></i>
                            <span>Belum Bayar</span>
                        </a>
                    </div>
                </div>

                <!-- Manajemen Admin -->
                <a href="{{ url('/superadmin') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->routeIs('superadmin.*') ? $activeMenuClass : '' }}"
                    aria-current="{{ request()->routeIs('superadmin.*') ? 'page' : 'false' }}">
                    <i class="fas fa-user-shield w-5"></i>
                    <span>Manajemen Admin</span>
                </a>

                <!-- Manajemen Assets -->
                <a href="{{ url('/assets') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->routeIs('assets.*') ? $activeMenuClass : '' }}"
                    aria-current="{{ request()->routeIs('assets.*') ? 'page' : 'false' }}">
                    <i class="fas fa-image w-5"></i>
                    <span>Manajemen Assets</span>
                </a>
                
                <!-- Active Users -->
                <a href="{{ url('/active-users') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->is('active-users*') ? $activeMenuClass : '' }}"
                    aria-current="{{ request()->is('active-users*') ? 'page' : 'false' }}">
                    <i class="fas fa-user-clock w-5"></i>
                    <span>Active User</span>
                </a>

                <!-- Manajemen Pelatihan Dropdown -->
                <div>
                    <button type="button" onclick="togglePelatihanDropdown()"
                        class="w-full flex items-center justify-between gap-3 px-4 py-2 rounded-lg hover:bg-blue-500 transition {{ $isTrainingMenuOpen ? 'bg-blue-500 shadow-md' : '' }}"
                        aria-controls="pelatihanDropdown" aria-expanded="{{ $isTrainingMenuOpen ? 'true' : 'false' }}">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-book-open w-5"></i>
                            <span>Manajemen Pelatihan</span>
                        </span>
                        <i id="pelatihanArrow" class="fas fa-chevron-down transition-transform {{ $isTrainingMenuOpen ? 'rotate-180' : '' }}"></i>
                    </button>

                    <div id="pelatihanDropdown" class="{{ $isTrainingMenuOpen ? '' : 'hidden' }} pl-10 mt-2 space-y-2">
                        <a href="{{ url('/learning_paths') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->routeIs('learning_paths.*') ? $activeMenuClass : '' }}"
                            aria-current="{{ request()->routeIs('learning_paths.*') ? 'page' : 'false' }}">
                            <i class="fas fa-route w-4"></i>
                            <span>Learning Path</span>
                        </a>
                        <a href="{{ url('/courses') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->routeIs('courses.*') ? $activeMenuClass : '' }}"
                            aria-current="{{ request()->routeIs('courses.*') ? 'page' : 'false' }}">
                            <i class="fas fa-book-open-reader w-4"></i>
                            <span>Courses</span>
                        </a>
                        <a href="{{ route('topics.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->routeIs('topics.*') ? $activeMenuClass : '' }}"
                            aria-current="{{ request()->routeIs('topics.*') ? 'page' : 'false' }}">
                            <i class="fas fa-list-check w-4"></i>
                            <span>Topics</span>
                        </a>
                        <a href="{{ route('lessons.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->routeIs('lessons.*') ? $activeMenuClass : '' }}"
                            aria-current="{{ request()->routeIs('lessons.*') ? 'page' : 'false' }}">
                            <i class="fas fa-book-open w-4"></i>
                            <span>Lessons</span>
                        </a>
                        <a href="{{ url('/webinars') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->routeIs('webinars.*') ? $activeMenuClass : '' }}"
                            aria-current="{{ request()->routeIs('webinars.*') ? 'page' : 'false' }}">
                            <i class="fas fa-video w-4"></i>
                            <span>Webinar</span>
                        </a>
                        <a href="{{ route('certificate.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->routeIs('certificate.*') ? $activeMenuClass : '' }}"
                            aria-current="{{ request()->routeIs('certificate.*') ? 'page' : 'false' }}">
                            <i class="fas fa-file-circle-check w-4 text-amber-200"></i>
                            <span>Sertifikat</span>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Logout -->
            <div class="p-4 shrink-0 border-t border-blue-500">
                <!-- Tombol Logout -->
                <a href="#"
                    onclick="confirmLogout(event)"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-500 transition">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Overlay untuk menutup sidebar pada layar kecil -->
        <div id="sidebarBackdrop" onclick="closeMobileSidebar()"
            class="hidden fixed inset-0 bg-slate-950/50 z-40 md:hidden"></div>

        <!-- Content area -->
        <div class="flex-1 min-w-0 flex flex-col md:ml-64">
            <!-- Topbar for mobile -->
            <header class="md:hidden sticky top-0 z-30 bg-white p-4 shadow">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-blue-700">SATUKELAS</span>
                    </div>
                    <button type="button" onclick="toggleMobileSidebar()"
                        class="p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition"
                        aria-label="Buka menu">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </header>

            <main class="flex-1 min-w-0 overflow-auto p-4 md:p-6">
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
            const button = document.querySelector('[aria-controls="userDropdown"]');
            d.classList.toggle('hidden');
            a.classList.toggle('rotate-180');
            button.setAttribute('aria-expanded', String(!d.classList.contains('hidden')));
        }

        function togglePelatihanDropdown() {
            const d = document.getElementById('pelatihanDropdown');
            const a = document.getElementById('pelatihanArrow');
            const button = document.querySelector('[aria-controls="pelatihanDropdown"]');
            d.classList.toggle('hidden');
            a.classList.toggle('rotate-180');
            button.setAttribute('aria-expanded', String(!d.classList.contains('hidden')));
        }

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const isClosed = sidebar.classList.contains('-translate-x-full');
            setMobileSidebarState(isClosed);
        }

        function setMobileSidebarState(isOpen) {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');

            sidebar.classList.toggle('-translate-x-full', !isOpen);
            backdrop.classList.toggle('hidden', !isOpen);
            document.body.classList.toggle('overflow-hidden', isOpen && window.innerWidth < 768);
        }

        function closeMobileSidebar() {
            setMobileSidebarState(false);
        }

        function handleSidebarResize() {
            if (window.innerWidth >= 768) {
                setMobileSidebarState(true);
            } else {
                setMobileSidebarState(false);
            }
        }

        window.addEventListener('resize', handleSidebarResize);
        window.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeMobileSidebar();
            }
        });
    </script>
</body>

</html>
