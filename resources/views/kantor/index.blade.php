@extends('layouts.app')

@section('title', 'Data Kantor - E-Ticketing System')

@section('content')
    <div class="flex min-h-screen bg-[#001D39]">
        <!-- Include sidebar -->
        @include('layouts.sidebar')

        <!-- Konten halaman -->
        <div class="flex-1 p-8 overflow-y-auto" style="height: 100vh;">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Data Kantor</h1>
                <p class="text-gray-400">Kelola data cabang dan kantor dalam sistem</p>
            </div>

            <!-- Filter Section -->
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <input type="text" placeholder="Cari kode/nama/area cabang..."
                            class="bg-white/5 border border-white/10 text-gray-300 text-sm rounded-lg pl-10 pr-4 py-2.5 w-80 focus:border-blue-400 focus:outline-none transition-colors"
                            id="searchInput" value="{{ request('search') }}">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                @if ($permissions['can_create'])
                    <button onclick="openCreateModal()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg transition-colors duration-200 flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Kantor Baru
                    </button>
                @endif
            </div>

            <!-- Table Container untuk live search -->
            <div id="table-container">
                @include('kantor.table')
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div id="kantorModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-[#001D39] border border-white/10 rounded-2xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-white" id="modalTitle">Tambah Kantor</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="kantorForm">
                @csrf
                <input type="hidden" id="kantorId" name="kantorId">

                <div class="mb-4">
                    <label for="kode_cabang" class="block text-sm font-medium text-gray-300 mb-2">Kode Cabang</label>
                    <input type="text" id="kode_cabang" name="kode_cabang"
                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 focus:border-blue-400 focus:outline-none transition-colors"
                        placeholder="Masukkan kode cabang (cth: 001-050)">
                    <div id="kode_cabang_error" class="text-red-400 text-xs mt-1 hidden"></div>
                </div>

                <div class="mb-4">
                    <label for="nama_cabang" class="block text-sm font-medium text-gray-300 mb-2">Nama Cabang</label>
                    <input type="text" id="nama_cabang" name="nama_cabang"
                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 focus:border-blue-400 focus:outline-none transition-colors"
                        placeholder="Masukkan nama cabang">
                    <div id="nama_cabang_error" class="text-red-400 text-xs mt-1 hidden"></div>
                </div>

                <div class="mb-4 relative">
                    <label for="area" class="block text-sm font-medium text-gray-300 mb-2">Area</label>
                    <select id="area" name="area"
                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 focus:border-blue-400 focus:outline-none transition-colors appearance-none cursor-pointer">
                        <option value="" class="bg-[#001D39] text-gray-400">Pilih Area</option>
                        <option value="1" class="bg-[#001D39] text-white">1</option>
                        <option value="2" class="bg-[#001D39] text-white">2</option>
                        <option value="3" class="bg-[#001D39] text-white">3</option>
                    </select>
                    <!-- Icon dropdown -->
                    <div class="pointer-events-none absolute right-3 bottom-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div id="area_error" class="text-red-400 text-xs mt-1 hidden"></div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeModal()"
                        class="flex-1 px-4 py-2.5 border border-white/10 text-gray-300 rounded-lg hover:bg-white/5 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-[#001D39] border border-white/10 rounded-2xl w-full max-w-md p-6">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Konfirmasi Hapus</h3>
                <p class="text-gray-400 mb-6">Apakah Anda yakin ingin menghapus kantor <span id="deleteKantorName"
                        class="text-white font-semibold"></span>?</p>

                <form id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="deleteKantorId" name="kantorId">

                    <div class="flex gap-3">
                        <button type="button" onclick="closeDeleteModal()"
                            class="flex-1 px-4 py-2.5 border border-white/10 text-gray-300 rounded-lg hover:bg-white/5 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg transition-colors">
                            Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom scrollbar untuk konten */
        .overflow-y-auto::-webkit-scrollbar {
            width: 5px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.3);
            border-radius: 10px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.6);
        }

        /* Modal animation */
        #kantorModal,
        #deleteModal {
            transition: opacity 0.3s ease;
        }

        /* Loading indicator */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Memastikan konten tidak terpotong */
        .ml-72 {
            margin-left: 18rem;
        }

        /* ============================================ */
        /* RESPONSIVE UNTUK MOBILE SAJA (max-width: 768px) */
        /* TIDAK MENGUBAH TAMPILAN PC/LAPTOP */
        /* ============================================ */
        @media (max-width: 768px) {

            /* Konten utama - kurangi padding */
            .flex-1.p-8 {
                padding: 1rem !important;
            }

            /* Header - perkecil margin dan font */
            .mb-8 {
                margin-bottom: 1rem !important;
            }

            .text-3xl {
                font-size: 1.25rem !important;
                line-height: 1.75rem !important;
            }

            .text-gray-400 {
                font-size: 0.75rem !important;
            }

            /* Filter section - buat column */
            .mb-6.flex.items-center.justify-between {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.75rem !important;
            }

            /* Search input - full width */
            .relative .w-80 {
                width: 100% !important;
            }

            /* Tombol tambah - full width */
            .bg-blue-600 {
                width: 100% !important;
                justify-content: center !important;
            }

            /* Tabel - scroll horizontal */
            #table-container {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
            }

            #table-container table {
                min-width: 600px !important;
            }

            #table-container table td,
            #table-container table th {
                font-size: 11px !important;
                padding: 8px 6px !important;
                white-space: nowrap !important;
            }

            /* Action buttons di tabel */
            #table-container .flex.items-center.gap-2 {
                gap: 4px !important;
            }

            #table-container button,
            #table-container a {
                padding: 4px 6px !important;
            }

            #table-container svg {
                width: 14px !important;
                height: 14px !important;
            }

            /* Pagination - perkecil */
            #table-container nav[role="navigation"] div,
            #table-container nav[role="navigation"] a,
            #table-container nav[role="navigation"] span {
                font-size: 11px !important;
                padding: 4px 8px !important;
            }

            /* Modal - perkecil padding */
            #kantorModal .p-6,
            #deleteModal .p-6 {
                padding: 1rem !important;
                margin: 1rem !important;
            }

            #kantorModal .max-w-md,
            #deleteModal .max-w-md {
                max-width: calc(100% - 2rem) !important;
            }

            /* Modal header */
            #kantorModal .text-xl,
            #deleteModal .text-xl {
                font-size: 1rem !important;
            }

            /* Modal form */
            #kantorForm .mb-4 {
                margin-bottom: 0.75rem !important;
            }

            #kantorForm label {
                font-size: 0.75rem !important;
            }

            #kantorForm input,
            #kantorForm select {
                padding: 0.5rem 0.75rem !important;
                font-size: 0.8rem !important;
            }

            /* Modal buttons */
            #kantorForm .flex.gap-3,
            #deleteForm .flex.gap-3 {
                flex-direction: column !important;
                gap: 0.5rem !important;
            }

            #kantorForm button,
            #deleteForm button {
                width: 100% !important;
                padding: 0.625rem !important;
            }

            /* Delete modal icon */
            #deleteModal .w-16.h-16 {
                width: 3rem !important;
                height: 3rem !important;
            }

            #deleteModal .w-8.h-8 {
                width: 1.5rem !important;
                height: 1.5rem !important;
            }

            #deleteModal .mb-6 {
                margin-bottom: 1rem !important;
            }
        }

        /* Tablet (769px - 1024px) - penyesuaian ringan */
        @media (min-width: 769px) and (max-width: 1024px) {
            .flex-1.p-8 {
                padding: 1.5rem !important;
            }

            .relative .w-80 {
                width: 240px !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Simpan permissions dari PHP ke JavaScript
        const permissions = @json($permissions);

        // Open create modal
        function openCreateModal() {
            if (!permissions.can_create) {
                alert('Anda tidak memiliki izin untuk menambah data');
                return;
            }
            document.getElementById('modalTitle').textContent = 'Tambah Kantor';
            document.getElementById('kantorId').value = '';
            document.getElementById('kode_cabang').value = '';
            document.getElementById('nama_cabang').value = '';
            document.getElementById('area').value = '';

            // Hide all error messages
            document.querySelectorAll('[id$="_error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });

            document.getElementById('kantorModal').classList.remove('hidden');
            document.getElementById('kantorModal').classList.add('flex');
        }

        // Open edit modal
        function openEditModal(id, kodeCabang, namaCabang, area) {
            if (!permissions.can_edit) {
                alert('Anda tidak memiliki izin untuk mengedit data');
                return;
            }
            document.getElementById('modalTitle').textContent = 'Edit Kantor';
            document.getElementById('kantorId').value = id;
            document.getElementById('kode_cabang').value = kodeCabang;
            document.getElementById('nama_cabang').value = namaCabang;
            document.getElementById('area').value = area;

            // Hide all error messages
            document.querySelectorAll('[id$="_error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });

            document.getElementById('kantorModal').classList.remove('hidden');
            document.getElementById('kantorModal').classList.add('flex');
        }

        // Close modal
        function closeModal() {
            document.getElementById('kantorModal').classList.add('hidden');
            document.getElementById('kantorModal').classList.remove('flex');
            document.getElementById('kantorForm').reset();
            document.querySelectorAll('[id$="_error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });
        }

        // Open delete modal
        function openDeleteModal(id, namaCabang) {
            if (!permissions.can_delete) {
                alert('Anda tidak memiliki izin untuk menghapus data');
                return;
            }
            document.getElementById('deleteKantorId').value = id;
            document.getElementById('deleteKantorName').textContent = namaCabang;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        // Close delete modal
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
            document.getElementById('deleteForm').reset();
        }

        // Handle form submit
        document.getElementById('kantorForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const kantorId = document.getElementById('kantorId').value;
            const url = kantorId ? `/kantor/${kantorId}` : '/kantor';
            const method = kantorId ? 'PUT' : 'POST';

            // Clear previous errors
            document.querySelectorAll('[id$="_error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });

            // Disable submit button to prevent double submission
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="loading"></span> Menyimpan...';

            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        kode_cabang: document.getElementById('kode_cabang').value,
                        nama_cabang: document.getElementById('nama_cabang').value,
                        area: document.getElementById('area').value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'error' && data.errors) {
                        // Handle validation errors
                        Object.keys(data.errors).forEach(field => {
                            const errorElement = document.getElementById(field + '_error');
                            if (errorElement) {
                                errorElement.textContent = data.errors[field][0];
                                errorElement.classList.remove('hidden');
                            }
                        });
                    } else if (data.status === 'success') {
                        // Success
                        closeModal();
                        window.location.reload();
                    } else {
                        alert(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyimpan data');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Simpan';
                });
        });

        // Handle delete submit
        document.getElementById('deleteForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const kantorId = document.getElementById('deleteKantorId').value;

            // Disable submit button
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="loading"></span> Menghapus...';

            fetch(`/kantor/${kantorId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        closeDeleteModal();
                        window.location.reload();
                    } else {
                        alert(data.message || 'Gagal menghapus data');
                        closeDeleteModal();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus data');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Hapus';
                });
        });

        // LIVE SEARCH FUNCTIONALITY
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        const tableContainer = document.getElementById('table-container');

        function fetchKantors() {
            const search = searchInput.value;

            // Show loading state
            tableContainer.innerHTML =
                '<div class="text-center py-8"><div class="loading mx-auto"></div><p class="text-gray-400 mt-2">Loading...</p></div>';

            // Build URL with query parameters
            const url = new URL(window.location.href);
            url.searchParams.set('search', search);

            // Fetch data
            fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    tableContainer.innerHTML = html;
                    // Re-attach event listeners to new pagination links
                    attachPaginationListeners();
                })
                .catch(error => {
                    console.error('Error:', error);
                    tableContainer.innerHTML = '<div class="text-center py-8 text-red-400">Error loading data</div>';
                });
        }

        // Attach listeners to pagination links
        function attachPaginationListeners() {
            document.querySelectorAll('.pagination-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = new URL(this.href);
                    // Preserve search parameter
                    url.searchParams.set('search', searchInput.value);

                    // Show loading
                    tableContainer.innerHTML =
                        '<div class="text-center py-8"><div class="loading mx-auto"></div><p class="text-gray-400 mt-2">Loading...</p></div>';

                    fetch(url.toString(), {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.text())
                        .then(html => {
                            tableContainer.innerHTML = html;
                            attachPaginationListeners();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            tableContainer.innerHTML =
                                '<div class="text-center py-8 text-red-400">Error loading data</div>';
                        });
                });
            });
        }

        // Search input with debounce
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(fetchKantors, 500);
            });
        }

        // Initial attach of pagination listeners
        attachPaginationListeners();

        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('kantorModal');
            const deleteModal = document.getElementById('deleteModal');

            if (e.target === modal) {
                closeModal();
            }
            if (e.target === deleteModal) {
                closeDeleteModal();
            }
        });
    </script>
@endpush
