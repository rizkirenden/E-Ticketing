@extends('layouts.app')

@section('title', 'Data Jenis Aplikasi - E-Ticketing System')

@section('content')
    <div class="flex min-h-screen bg-[#001D39]">
        <!-- Include sidebar - sudah fixed dari sisi component -->
        @include('layouts.sidebar')

        <!-- Konten halaman dengan margin left selebar sidebar dan bisa discroll -->
        <div class="flex-1 p-6 overflow-y-auto" style="height: 100vh;">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Data Jenis Aplikasi</h1>
                <p class="text-gray-400">Kelola jenis aplikasi dalam sistem</p>
            </div>

            <!-- Filter Section -->
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <input type="text" placeholder="Cari jenis aplikasi atau deskripsi..."
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
                        Tambah Jenis Aplikasi Baru
                    </button>
                @endif
            </div>

            <!-- Table Container untuk live search -->
            <div id="table-container">
                @include('jenis_aplikasi.table')
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div id="jenisAplikasiModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-[#001D39] border border-white/10 rounded-2xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-white" id="modalTitle">Tambah Jenis Aplikasi</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="jenisAplikasiForm">
                @csrf
                <input type="hidden" id="jenisAplikasiId" name="jenisAplikasiId">

                <div class="mb-4">
                    <label for="kode_jenis_aplikasi" class="block text-sm font-medium text-gray-300 mb-2">Kode Jenis
                        Aplikasi</label>
                    <input type="text" id="kode_jenis_aplikasi" name="kode_jenis_aplikasi"
                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 focus:border-blue-400 focus:outline-none transition-colors"
                        placeholder="Masukkan kode jenis aplikasi (cth: DIG, MIB, ATM)">
                    <div id="kode_jenis_aplikasi_error" class="text-red-400 text-xs mt-1 hidden"></div>
                </div>

                <div class="mb-4">
                    <label for="jenis_aplikasi" class="block text-sm font-medium text-gray-300 mb-2">Jenis Aplikasi</label>
                    <input type="text" id="jenis_aplikasi" name="jenis_aplikasi"
                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 focus:border-blue-400 focus:outline-none transition-colors"
                        placeholder="Masukkan jenis aplikasi (cth: Digimod, Mibas, ATM)">
                    <div id="jenis_aplikasi_error" class="text-red-400 text-xs mt-1 hidden"></div>
                </div>

                <div class="mb-4">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                    <input type="text" id="deskripsi" name="deskripsi"
                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 focus:border-blue-400 focus:outline-none transition-colors"
                        placeholder="Masukkan deskripsi jenis aplikasi">
                    <div id="deskripsi_error" class="text-red-400 text-xs mt-1 hidden"></div>
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
                <p class="text-gray-400 mb-6">Apakah Anda yakin ingin menghapus jenis aplikasi <span
                        id="deleteJenisAplikasiName" class="text-white font-semibold"></span>?</p>

                <form id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="deleteJenisAplikasiId" name="jenisAplikasiId">

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

        /* Memastikan konten tidak terpotong */
        .ml-72 {
            margin-left: 18rem;
        }

        /* Modal animation */
        #jenisAplikasiModal,
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

        /* Styling untuk tabel agar responsif */
        #table-container {
            width: 100%;
            overflow-x: auto;
        }

        /* Memastikan card dan konten lainnya tidak overflow */
        .bg-white\/5 {
            width: 100%;
        }

        /* Toast notification */
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* ============================================ */
        /* RESPONSIVE UNTUK MOBILE SAJA (max-width: 768px) */
        /* TIDAK MENGUBAH TAMPILAN PC/LAPTOP */
        /* ============================================ */
        @media (max-width: 768px) {

            /* Konten utama - kurangi padding */
            .flex-1.p-6 {
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
                min-width: 500px !important;
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
            #jenisAplikasiModal .p-6,
            #deleteModal .p-6 {
                padding: 1rem !important;
                margin: 1rem !important;
            }

            #jenisAplikasiModal .max-w-md,
            #deleteModal .max-w-md {
                max-width: calc(100% - 2rem) !important;
            }

            /* Modal header */
            #jenisAplikasiModal .text-xl,
            #deleteModal .text-xl {
                font-size: 1rem !important;
            }

            /* Modal form */
            #jenisAplikasiForm .mb-4 {
                margin-bottom: 0.75rem !important;
            }

            #jenisAplikasiForm label {
                font-size: 0.75rem !important;
            }

            #jenisAplikasiForm input {
                padding: 0.5rem 0.75rem !important;
                font-size: 0.8rem !important;
            }

            /* Modal buttons */
            #jenisAplikasiForm .flex.gap-3,
            #deleteForm .flex.gap-3 {
                flex-direction: column !important;
                gap: 0.5rem !important;
            }

            #jenisAplikasiForm button,
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

            /* Toast notification di mobile */
            .toast-notification {
                top: 10px !important;
                right: 10px !important;
                left: 10px !important;
                width: auto !important;
                font-size: 12px !important;
                padding: 10px !important;
            }
        }

        /* Tablet (769px - 1024px) - penyesuaian ringan */
        @media (min-width: 769px) and (max-width: 1024px) {
            .flex-1.p-6 {
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

        // Fungsi untuk menampilkan notifikasi
        function showNotification(message, type = 'success') {
            // Buat element notifikasi
            const notification = document.createElement('div');
            notification.className =
                `toast-notification bg-${type === 'success' ? 'green' : 'red'}-500 text-white px-6 py-3 rounded-lg shadow-lg`;
            notification.innerHTML = `
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="${type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}">
                        </path>
                    </svg>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(notification);

            // Hapus notifikasi setelah 3 detik
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Fungsi untuk refresh tabel
        function refreshTable() {
            const search = searchInput?.value || '';
            const url = new URL(window.location.href);
            url.searchParams.set('search', search);

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
                    console.error('Error refreshing table:', error);
                    showNotification('Gagal memuat ulang data', 'error');
                });
        }

        // Open create modal
        function openCreateModal() {
            if (!permissions.can_create) {
                showNotification('Anda tidak memiliki izin untuk menambah data', 'error');
                return;
            }
            document.getElementById('modalTitle').textContent = 'Tambah Jenis Aplikasi';
            document.getElementById('jenisAplikasiId').value = '';
            document.getElementById('kode_jenis_aplikasi').value = '';
            document.getElementById('jenis_aplikasi').value = '';
            document.getElementById('deskripsi').value = '';

            // Hide all error messages
            document.querySelectorAll('[id$="_error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });

            document.getElementById('jenisAplikasiModal').classList.remove('hidden');
            document.getElementById('jenisAplikasiModal').classList.add('flex');
        }

        // Close modal
        function closeModal() {
            document.getElementById('jenisAplikasiModal').classList.add('hidden');
            document.getElementById('jenisAplikasiModal').classList.remove('flex');
        }

        // Open edit modal
        function openEditModal(id, kodeJenisAplikasi, jenisAplikasi, deskripsi) {
            if (!permissions.can_edit) {
                showNotification('Anda tidak memiliki izin untuk mengedit data', 'error');
                return;
            }
            document.getElementById('modalTitle').textContent = 'Edit Jenis Aplikasi';
            document.getElementById('jenisAplikasiId').value = id;
            document.getElementById('kode_jenis_aplikasi').value = kodeJenisAplikasi;
            document.getElementById('jenis_aplikasi').value = jenisAplikasi;
            document.getElementById('deskripsi').value = deskripsi;

            // Hide all error messages
            document.querySelectorAll('[id$="_error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });

            document.getElementById('jenisAplikasiModal').classList.remove('hidden');
            document.getElementById('jenisAplikasiModal').classList.add('flex');
        }

        // Open delete modal
        function openDeleteModal(id, name) {
            if (!permissions.can_delete) {
                showNotification('Anda tidak memiliki izin untuk menghapus data', 'error');
                return;
            }
            document.getElementById('deleteJenisAplikasiId').value = id;
            document.getElementById('deleteJenisAplikasiName').textContent = name;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        // Close delete modal
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }

        // Handle form submit
        document.getElementById('jenisAplikasiForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const jenisAplikasiId = document.getElementById('jenisAplikasiId').value;
            const url = jenisAplikasiId ? `/jenis-aplikasi/${jenisAplikasiId}` : '/jenis-aplikasi';
            const method = jenisAplikasiId ? 'PUT' : 'POST';

            // Clear previous errors
            document.querySelectorAll('[id$="_error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });

            // Disable submit button
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
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
                        kode_jenis_aplikasi: document.getElementById('kode_jenis_aplikasi').value,
                        jenis_aplikasi: document.getElementById('jenis_aplikasi').value,
                        deskripsi: document.getElementById('deskripsi').value
                    })
                })
                .then(async response => {
                    const data = await response.json();

                    if (!response.ok) {
                        if (response.status === 422 && data.errors) {
                            // Validation errors
                            Object.keys(data.errors).forEach(field => {
                                const errorElement = document.getElementById(field + '_error');
                                if (errorElement) {
                                    errorElement.textContent = data.errors[field][0];
                                    errorElement.classList.remove('hidden');
                                }
                            });
                            throw new Error('Validation failed');
                        } else {
                            throw new Error(data.message || 'Terjadi kesalahan pada server');
                        }
                    }

                    return data;
                })
                .then(data => {
                    // Success
                    closeModal();
                    showNotification(data.message, 'success');
                    refreshTable(); // Refresh tanpa reload halaman
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (error.message !== 'Validation failed') {
                        showNotification(error.message || 'Terjadi kesalahan saat menyimpan data', 'error');
                    }
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        });

        // Handle delete submit
        document.getElementById('deleteForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const jenisAplikasiId = document.getElementById('deleteJenisAplikasiId').value;

            // Disable submit button
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="loading"></span> Menghapus...';

            fetch(`/jenis-aplikasi/${jenisAplikasiId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    }
                })
                .then(async response => {
                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Gagal menghapus data');
                    }

                    return data;
                })
                .then(data => {
                    closeDeleteModal();
                    showNotification(data.message, 'success');
                    refreshTable(); // Refresh tanpa reload halaman
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification(error.message || 'Terjadi kesalahan saat menghapus data', 'error');
                    closeDeleteModal();
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        });

        // LIVE SEARCH FUNCTIONALITY
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        const tableContainer = document.getElementById('table-container');

        function fetchJenisAplikasis() {
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
                searchTimeout = setTimeout(fetchJenisAplikasis, 500);
            });
        }

        // Initial attach of pagination listeners
        attachPaginationListeners();

        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('jenisAplikasiModal');
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
