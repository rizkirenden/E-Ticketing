@extends('layouts.app')

@section('title', 'Data Role Aplikasi - E-Ticketing System')

@section('content')
    <div class="flex min-h-screen bg-[#001D39]">
        <!-- Include sidebar - sudah fixed dari sisi component -->
        @include('layouts.sidebar')

        <!-- Konten halaman dengan margin left selebar sidebar dan bisa discroll -->
        <div class="flex-1 p-6 overflow-y-auto" style="height: 100vh;">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Data Role Aplikasi</h1>
                <p class="text-gray-400">Kelola akses role ke aplikasi dalam sistem</p>
            </div>

            <!-- Filter Section -->
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <input type="text" placeholder="Cari role atau jenis aplikasi..."
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
                        Tambah Role Aplikasi Baru
                    </button>
                @endif
            </div>

            <!-- Table Container untuk live search -->
            <div id="table-container">
                @include('role_aplikasi.table')
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if ($permissions['can_create'] || $permissions['can_edit'])
        <div id="roleaplikasiModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-[#001D39] border border-white/10 rounded-2xl w-full max-w-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-white" id="modalTitle">Tambah Role Aplikasi</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <form id="roleaplikasiForm">
                    @csrf
                    <input type="hidden" id="roleaplikasiId" name="roleaplikasiId">

                    <div class="mb-4 relative">
                        <label for="role_id" class="block text-sm font-medium text-gray-300 mb-2">Role</label>
                        <select id="role_id" name="role_id"
                            class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 focus:border-blue-400 focus:outline-none transition-colors appearance-none cursor-pointer">
                            <option value="" class="bg-[#001D39] text-gray-400">Pilih Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" class="bg-[#001D39] text-white">{{ $role->nama_role }}
                                </option>
                            @endforeach
                        </select>
                        <!-- Icon dropdown -->
                        <div class="pointer-events-none absolute right-3 bottom-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </div>
                        <div id="role_id_error" class="text-red-400 text-xs mt-1 hidden"></div>
                    </div>

                    <div class="mb-4 relative">
                        <label for="jenis_aplikasi_id" class="block text-sm font-medium text-gray-300 mb-2">Jenis
                            Aplikasi</label>
                        <select id="jenis_aplikasi_id" name="jenis_aplikasi_id"
                            class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 focus:border-blue-400 focus:outline-none transition-colors appearance-none cursor-pointer">
                            <option value="" class="bg-[#001D39] text-gray-400">Pilih Jenis Aplikasi</option>
                            @foreach ($jenisAplikasis as $jenisAplikasi)
                                <option value="{{ $jenisAplikasi->id }}" class="bg-[#001D39] text-white">
                                    {{ $jenisAplikasi->jenis_aplikasi }} - {{ $jenisAplikasi->deskripsi }}</option>
                            @endforeach
                        </select>
                        <!-- Icon dropdown -->
                        <div class="pointer-events-none absolute right-3 bottom-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </div>
                        <div id="jenis_aplikasi_id_error" class="text-red-400 text-xs mt-1 hidden"></div>
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
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($permissions['can_delete'])
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
                    <p class="text-gray-400 mb-6">Apakah Anda yakin ingin menghapus role aplikasi <span
                            id="deleteRoleaplikasiName" class="text-white font-semibold"></span>?</p>

                    <form id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" id="deleteRoleaplikasiId" name="roleaplikasiId">

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
    @endif

    <!-- Import Modal -->
    @if ($permissions['can_import'])
        <div id="importModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-[#001D39] border border-white/10 rounded-2xl w-full max-w-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-white">Import Data Role Aplikasi</h3>
                    <button onclick="closeImportModal()" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="importForm" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="import_file" class="block text-sm font-medium text-gray-300 mb-2">File
                            Excel/CSV</label>
                        <input type="file" id="import_file" name="file" accept=".xlsx,.xls,.csv"
                            class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 focus:border-blue-400 focus:outline-none transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                        <p class="text-gray-400 text-xs mt-2">Format file: .xlsx, .xls, .csv (max 2MB)</p>
                        <div id="file_error" class="text-red-400 text-xs mt-1 hidden"></div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="closeImportModal()"
                            class="flex-1 px-4 py-2.5 border border-white/10 text-gray-300 rounded-lg hover:bg-white/5 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2.5 rounded-lg transition-colors">
                            Import Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
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
        #roleaplikasiModal,
        #deleteModal,
        #importModal {
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

        /* Memperbaiki posisi dropdown icon */
        .relative .absolute {
            pointer-events: none;
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
            #roleaplikasiModal .p-6,
            #deleteModal .p-6,
            #importModal .p-6 {
                padding: 1rem !important;
                margin: 1rem !important;
            }

            #roleaplikasiModal .max-w-md,
            #deleteModal .max-w-md,
            #importModal .max-w-md {
                max-width: calc(100% - 2rem) !important;
            }

            /* Modal header */
            #roleaplikasiModal .text-xl,
            #deleteModal .text-xl,
            #importModal .text-xl {
                font-size: 1rem !important;
            }

            /* Modal form */
            #roleaplikasiForm .mb-4 {
                margin-bottom: 0.75rem !important;
            }

            #roleaplikasiForm label {
                font-size: 0.75rem !important;
            }

            #roleaplikasiForm select {
                padding: 0.5rem 0.75rem !important;
                font-size: 0.8rem !important;
            }

            /* Modal buttons */
            #roleaplikasiForm .flex.gap-3,
            #deleteForm .flex.gap-3,
            #importForm .flex.gap-3 {
                flex-direction: column !important;
                gap: 0.5rem !important;
            }

            #roleaplikasiForm button,
            #deleteForm button,
            #importForm button {
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

            /* Import file input */
            #importForm input[type="file"] {
                font-size: 0.7rem !important;
            }

            #importForm .file\:mr-4 {
                margin-right: 0.5rem !important;
            }

            #importForm .file\:py-2 {
                padding-top: 0.25rem !important;
                padding-bottom: 0.25rem !important;
            }

            #importForm .file\:px-4 {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
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

        // Open create modal
        function openCreateModal() {
            if (!permissions.can_create) {
                alert('Anda tidak memiliki izin untuk menambah data');
                return;
            }
            document.getElementById('modalTitle').textContent = 'Tambah Role Aplikasi';
            document.getElementById('roleaplikasiId').value = '';
            document.getElementById('role_id').value = '';
            document.getElementById('jenis_aplikasi_id').value = '';

            // Hide all error messages
            document.querySelectorAll('[id$="_error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });

            document.getElementById('roleaplikasiModal').classList.remove('hidden');
            document.getElementById('roleaplikasiModal').classList.add('flex');
        }

        // Open edit modal
        function openEditModal(id, roleId, jenisAplikasiId, roleName, jenisAplikasiName) {
            if (!permissions.can_edit) {
                alert('Anda tidak memiliki izin untuk mengedit data');
                return;
            }
            document.getElementById('modalTitle').textContent = 'Edit Role Aplikasi';
            document.getElementById('roleaplikasiId').value = id;
            document.getElementById('role_id').value = roleId;
            document.getElementById('jenis_aplikasi_id').value = jenisAplikasiId;

            // Hide all error messages
            document.querySelectorAll('[id$="_error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });

            document.getElementById('roleaplikasiModal').classList.remove('hidden');
            document.getElementById('roleaplikasiModal').classList.add('flex');
        }

        // Close modal
        function closeModal() {
            document.getElementById('roleaplikasiModal').classList.add('hidden');
            document.getElementById('roleaplikasiModal').classList.remove('flex');
            document.getElementById('roleaplikasiForm').reset();
            document.querySelectorAll('[id$="_error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });
        }

        // Open delete modal
        function openDeleteModal(id, roleName, jenisAplikasiName) {
            if (!permissions.can_delete) {
                alert('Anda tidak memiliki izin untuk menghapus data');
                return;
            }
            document.getElementById('deleteRoleaplikasiId').value = id;
            document.getElementById('deleteRoleaplikasiName').textContent = roleName + ' - ' + jenisAplikasiName;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        // Close delete modal
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
            document.getElementById('deleteForm').reset();
        }

        // Export function
        function exportData() {
            if (!permissions.can_export) {
                alert('Anda tidak memiliki izin untuk export data');
                return;
            }

            const search = document.getElementById('searchInput').value;
            window.location.href = `/role-aplikasi/export?search=${search}`;
        }

        // Open import modal
        function openImportModal() {
            if (!permissions.can_import) {
                alert('Anda tidak memiliki izin untuk import data');
                return;
            }
            document.getElementById('importModal').classList.remove('hidden');
            document.getElementById('importModal').classList.add('flex');
        }

        // Close import modal
        function closeImportModal() {
            document.getElementById('importModal').classList.add('hidden');
            document.getElementById('importModal').classList.remove('flex');
            document.getElementById('importForm').reset();
            document.getElementById('file_error').classList.add('hidden');
        }

        // Handle form submit
        document.getElementById('roleaplikasiForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const roleaplikasiId = document.getElementById('roleaplikasiId').value;
            const url = roleaplikasiId ? `/role-aplikasi/${roleaplikasiId}` : '/role-aplikasi';
            const method = roleaplikasiId ? 'PUT' : 'POST';

            // Clear previous errors
            document.querySelectorAll('[id$="_error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });

            // Disable submit button
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
                        role_id: document.getElementById('role_id').value,
                        jenis_aplikasi_id: document.getElementById('jenis_aplikasi_id').value
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
                    } else if (data.status === 'error') {
                        // Handle custom error (like duplicate)
                        alert(data.message);
                    } else if (data.status === 'success') {
                        // Success
                        closeModal();
                        window.location.reload(); // Refresh to show updated data
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

            const roleaplikasiId = document.getElementById('deleteRoleaplikasiId').value;

            // Disable submit button
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="loading"></span> Menghapus...';

            fetch(`/role-aplikasi/${roleaplikasiId}`, {
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
                        window.location.reload(); // Refresh to show updated data
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

        // Handle import form submit
        document.getElementById('importForm')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            // Clear previous error
            document.getElementById('file_error').classList.add('hidden');

            // Disable submit button
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="loading"></span> Mengimport...';

            fetch('/role-aplikasi/import', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'error' && data.errors) {
                        // Handle validation errors
                        if (data.errors.file) {
                            const errorElement = document.getElementById('file_error');
                            errorElement.textContent = data.errors.file[0];
                            errorElement.classList.remove('hidden');
                        }
                    } else if (data.status === 'success') {
                        alert('Import data berhasil');
                        closeImportModal();
                        window.location.reload();
                    } else {
                        alert(data.message || 'Gagal import data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat import data');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Import Data';
                });
        });

        // LIVE SEARCH FUNCTIONALITY
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        const tableContainer = document.getElementById('table-container');

        function fetchRoleaplikasis() {
            const search = searchInput.value;

            // Show loading state
            tableContainer.innerHTML =
                '<div class="text-center py-8"><div class="loading mx-auto"></div><p class="text-gray-400 mt-2">Loading...</p></div>';

            // Build URL with query parameters
            const url = new URL(window.location.href);
            url.searchParams.set('search', search);

            // Update URL without reload
            window.history.pushState({}, '', url);

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
                searchTimeout = setTimeout(fetchRoleaplikasis, 500); // Debounce 500ms
            });
        }

        // Initial attach of pagination listeners
        attachPaginationListeners();

        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('roleaplikasiModal');
            const deleteModal = document.getElementById('deleteModal');
            const importModal = document.getElementById('importModal');

            if (e.target === modal) {
                closeModal();
            }
            if (e.target === deleteModal) {
                closeDeleteModal();
            }
            if (e.target === importModal) {
                closeImportModal();
            }
        });

        // Handle resize untuk menjaga konsistensi
        window.addEventListener('resize', function() {
            const contentDiv = document.querySelector('.ml-72');
            if (contentDiv) {
                if (window.innerWidth < 768) {
                    contentDiv.style.marginLeft = '0';
                } else {
                    contentDiv.style.marginLeft = '18rem';
                }
            }
        });

        // Handle browser back/forward buttons
        window.addEventListener('popstate', function() {
            fetchRoleaplikasis();
        });
    </script>
@endpush
