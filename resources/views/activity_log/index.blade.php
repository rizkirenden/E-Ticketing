@extends('layouts.app')

@section('title', 'Activity Logs - E-Ticketing System')

@section('content')
    <div class="flex min-h-screen bg-[#001D39]">
        @include('layouts.sidebar')

        <div class="flex-1 p-6 overflow-y-auto" style="height: 100vh;">
            <!-- Header -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Activity Logs</h1>
                    <p class="text-gray-400">Catatan aktivitas pengguna dalam sistem</p>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="refreshStatistics()"
                        class="px-4 py-2.5 bg-white/5 border border-white/10 text-gray-300 rounded-lg hover:bg-white/10 transition-colors text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh
                    </button>
                    <button onclick="openPdfModal()"
                        class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export PDF
                    </button>
                    <button onclick="exportLogs()"
                        class="px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export JSON
                    </button>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gradient-to-br from-blue-500/20 to-purple-500/20 border border-blue-500/30 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-xs">Total Hari Ini</p>
                            <p class="text-2xl font-bold text-white" id="stat-today">0</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-gradient-to-br from-green-500/20 to-emerald-500/20 border border-green-500/30 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-xs">Total Minggu Ini</p>
                            <p class="text-2xl font-bold text-white" id="stat-week">0</p>
                        </div>
                        <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-purple-500/20 to-pink-500/20 border border-purple-500/30 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-xs">Total Bulan Ini</p>
                            <p class="text-2xl font-bold text-white" id="stat-month">0</p>
                        </div>
                        <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-gradient-to-br from-yellow-500/20 to-orange-500/20 border border-yellow-500/30 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-xs">Total Tahun Ini</p>
                            <p class="text-2xl font-bold text-white" id="stat-year">0</p>
                        </div>
                        <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="mb-6">
                <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl p-4">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-3 items-end">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-300 mb-1">Pencarian</label>
                            <div class="relative">
                                <input type="text" placeholder="Cari aktivitas, deskripsi, user..."
                                    class="w-full bg-white/5 border border-white/10 text-gray-300 rounded-lg pl-10 pr-3 py-2 text-sm focus:border-blue-400 focus:outline-none transition-all"
                                    id="searchInput" value="{{ request('search') }}">
                                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <label for="filterModul" class="block text-xs font-medium text-gray-300 mb-1">Modul</label>
                            <select id="filterModul"
                                class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-3 py-2 text-sm focus:border-blue-400 focus:outline-none transition-all">
                                <option value="">Semua Modul</option>
                                @foreach ($modules as $module)
                                    <option value="{{ $module }}"
                                        {{ request('modul') == $module ? 'selected' : '' }}>{{ $module }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="filterAktivitas"
                                class="block text-xs font-medium text-gray-300 mb-1">Aktivitas</label>
                            <select id="filterAktivitas"
                                class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-3 py-2 text-sm focus:border-blue-400 focus:outline-none transition-all">
                                <option value="">Semua Aktivitas</option>
                                @foreach ($activities as $activity)
                                    <option value="{{ $activity }}"
                                        {{ request('aktivitas') == $activity ? 'selected' : '' }}>{{ $activity }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="tanggalAwal" class="block text-xs font-medium text-gray-300 mb-1">Tanggal
                                Awal</label>
                            <input type="date" id="tanggalAwal" value="{{ request('tanggal_awal') }}"
                                class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-3 py-2 text-sm focus:border-blue-400 focus:outline-none transition-all date-input-white">
                        </div>
                        <div>
                            <label for="tanggalAkhir" class="block text-xs font-medium text-gray-300 mb-1">Tanggal
                                Akhir</label>
                            <input type="date" id="tanggalAkhir" value="{{ request('tanggal_akhir') }}"
                                class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-3 py-2 text-sm focus:border-blue-400 focus:outline-none transition-all date-input-white">
                        </div>
                        <div>
                            <button onclick="resetFilters()"
                                class="w-full bg-white/5 hover:bg-white/10 border border-white/10 text-gray-300 rounded-lg px-3 py-2 text-sm transition-colors flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div id="table-container">
                @include('activity_log.table')
            </div>
        </div>
    </div>

    <!-- Modal Detail Log -->
    <div id="logModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-[#001D39] border border-white/10 rounded-2xl w-full max-w-4xl p-6 max-h-[80vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-white">Detail Activity Log</h3>
                <button onclick="closeLogModal()" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="logDetailContent" class="space-y-4">
                <div class="text-center py-8">
                    <div class="loading mx-auto"></div>
                    <p class="text-gray-400 mt-2">Loading...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pilih Orientasi PDF -->
    <div id="pdfModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-[#001D39] border border-white/10 rounded-2xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-white">Pilih Orientasi PDF</h3>
                <button onclick="closePdfModal()" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="pdfForm" action="{{ url('/activity-logs/export-pdf') }}" method="GET" target="_blank">
                <input type="hidden" name="search" id="pdfSearch">
                <input type="hidden" name="modul" id="pdfModul">
                <input type="hidden" name="aktivitas" id="pdfAktivitas">
                <input type="hidden" name="tanggal_awal" id="pdfTanggalAwal">
                <input type="hidden" name="tanggal_akhir" id="pdfTanggalAkhir">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-300 mb-3">Pilih Orientasi Halaman</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="orientation" value="portrait" class="sr-only peer" checked>
                            <div
                                class="p-4 bg-white/5 border border-white/10 rounded-xl text-center hover:bg-white/10 peer-checked:border-blue-400 peer-checked:bg-blue-400/10 transition-all">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <rect x="2" y="2" width="20" height="20" rx="2" stroke="currentColor"
                                        fill="none" stroke-width="1.5" />
                                </svg>
                                <span class="text-white font-medium">Portrait</span>
                                <span class="text-xs text-gray-400 block mt-1">Vertikal</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="orientation" value="landscape" class="sr-only peer">
                            <div
                                class="p-4 bg-white/5 border border-white/10 rounded-xl text-center hover:bg-white/10 peer-checked:border-blue-400 peer-checked:bg-blue-400/10 transition-all">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <rect x="2" y="2" width="20" height="20" rx="2" stroke="currentColor"
                                        fill="none" stroke-width="1.5" transform="rotate(90 12 12)" />
                                </svg>
                                <span class="text-white font-medium">Landscape</span>
                                <span class="text-xs text-gray-400 block mt-1">Horizontal</span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closePdfModal()"
                        class="flex-1 px-4 py-2.5 border border-white/10 text-gray-300 rounded-lg hover:bg-white/5 transition-colors">Batal</button>
                    <button type="submit"
                        class="flex-1 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white px-4 py-2.5 rounded-lg transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* Scrollbar */
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

        /* Loading */
        .loading {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 3px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #3b82f6;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Date input */
        .date-input-white {
            color-scheme: dark;
        }

        .date-input-white::-webkit-calendar-picker-indicator {
            filter: invert(1) brightness(100%);
            cursor: pointer;
        }

        /* JSON Viewer */
        .json-viewer {
            background: #0a1a2f;
            border-radius: 8px;
            padding: 16px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            overflow-x: auto;
        }

        .json-key {
            color: #f472b6;
        }

        .json-value {
            color: #4ade80;
        }

        .json-string {
            color: #fbbf24;
        }

        .json-number {
            color: #60a5fa;
        }

        .json-boolean {
            color: #f87171;
        }

        .json-null {
            color: #9ca3af;
        }

        /* FIX DROPDOWN COLORS - Warna sesuai tema awal */
        select {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #e5e7eb !important;
        }

        select option {
            background-color: #001D39 !important;
            color: #e5e7eb !important;
            padding: 8px !important;
        }

        select option:hover,
        select option:focus {
            background: #1e3a5f !important;
            color: white !important;
        }

        select option:checked {
            background: linear-gradient(135deg, #1e3a5f 0%, #0f2b4a 100%) !important;
            color: white !important;
        }

        /* Khusus untuk filter modul dan aktivitas */
        #filterModul,
        #filterAktivitas {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #e5e7eb !important;
        }

        #filterModul option,
        #filterAktivitas option {
            background-color: #001D39 !important;
            color: #e5e7eb !important;
        }

        #filterModul option:checked,
        #filterAktivitas option:checked {
            background: #1e3a5f !important;
            color: white !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .flex-1.p-6 {
                padding: 1rem !important;
            }

            .grid.grid-cols-1.md\:grid-cols-4 {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 0.75rem !important;
            }

            #table-container {
                overflow-x: auto !important;
            }

            #table-container table {
                min-width: 800px !important;
            }

            #table-container table td,
            #table-container table th {
                font-size: 10px !important;
                padding: 6px 4px !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        const filterModul = document.getElementById('filterModul');
        const filterAktivitas = document.getElementById('filterAktivitas');
        const tanggalAwal = document.getElementById('tanggalAwal');
        const tanggalAkhir = document.getElementById('tanggalAkhir');
        const tableContainer = document.getElementById('table-container');

        // Load statistics on page load
        function loadStatistics() {
            fetch('/activity-logs/statistics?period=today')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('stat-today').textContent = data.data.total;
                    }
                })
                .catch(error => console.error('Error loading today stats:', error));

            fetch('/activity-logs/statistics?period=week')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('stat-week').textContent = data.data.total;
                    }
                })
                .catch(error => console.error('Error loading week stats:', error));

            fetch('/activity-logs/statistics?period=month')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('stat-month').textContent = data.data.total;
                    }
                })
                .catch(error => console.error('Error loading month stats:', error));

            fetch('/activity-logs/statistics?period=year')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('stat-year').textContent = data.data.total;
                    }
                })
                .catch(error => console.error('Error loading year stats:', error));
        }

        function refreshStatistics() {
            loadStatistics();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Statistik berhasil diperbarui',
                timer: 1500,
                showConfirmButton: false
            });
        }

        function openPdfModal() {
            document.getElementById('pdfSearch').value = searchInput?.value || '';
            document.getElementById('pdfModul').value = filterModul?.value || '';
            document.getElementById('pdfAktivitas').value = filterAktivitas?.value || '';
            document.getElementById('pdfTanggalAwal').value = tanggalAwal?.value || '';
            document.getElementById('pdfTanggalAkhir').value = tanggalAkhir?.value || '';
            document.getElementById('pdfModal').classList.remove('hidden');
            document.getElementById('pdfModal').classList.add('flex');
        }

        function closePdfModal() {
            document.getElementById('pdfModal').classList.add('hidden');
            document.getElementById('pdfModal').classList.remove('flex');
        }

        function exportLogs() {
            Swal.fire({
                title: 'Mengekspor Data...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const url = new URL('/activity-logs/export', window.location.origin);
            if (searchInput?.value) url.searchParams.set('search', searchInput.value);
            if (filterModul?.value) url.searchParams.set('modul', filterModul.value);
            if (filterAktivitas?.value) url.searchParams.set('aktivitas', filterAktivitas.value);
            if (tanggalAwal?.value) url.searchParams.set('tanggal_awal', tanggalAwal.value);
            if (tanggalAkhir?.value) url.searchParams.set('tanggal_akhir', tanggalAkhir.value);

            fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.blob())
                .then(blob => {
                    Swal.close();
                    const a = document.createElement('a');
                    a.href = URL.createObjectURL(blob);
                    a.download = 'activity-logs-' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.json';
                    a.click();
                    URL.revokeObjectURL(a.href);
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Data berhasil diekspor',
                        timer: 2000,
                        showConfirmButton: false
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: error.message
                    });
                });
        }

        function fetchLogs() {
            const tglAwal = tanggalAwal?.value || '';
            const tglAkhir = tanggalAkhir?.value || '';
            if (tglAwal && tglAkhir && tglAwal > tglAkhir) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validasi',
                    text: 'Tanggal akhir harus lebih besar atau sama dengan tanggal awal'
                });
                return;
            }
            tableContainer.innerHTML =
                '<div class="text-center py-8"><div class="loading mx-auto"></div><p class="text-gray-400 mt-2">Loading...</p></div>';
            const url = new URL(window.location.href);
            url.searchParams.set('search', searchInput?.value || '');
            url.searchParams.set('modul', filterModul?.value || '');
            url.searchParams.set('aktivitas', filterAktivitas?.value || '');
            url.searchParams.set('tanggal_awal', tglAwal);
            url.searchParams.set('tanggal_akhir', tglAkhir);
            window.history.pushState({}, '', url);
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
                    tableContainer.innerHTML = '<div class="text-center py-8 text-red-400">Error loading data</div>';
                });
        }

        function attachPaginationListeners() {
            document.querySelectorAll('.pagination-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = new URL(this.href);
                    if (searchInput) url.searchParams.set('search', searchInput.value);
                    if (filterModul) url.searchParams.set('modul', filterModul.value);
                    if (filterAktivitas) url.searchParams.set('aktivitas', filterAktivitas.value);
                    if (tanggalAwal) url.searchParams.set('tanggal_awal', tanggalAwal.value);
                    if (tanggalAkhir) url.searchParams.set('tanggal_akhir', tanggalAkhir.value);
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
                            tableContainer.innerHTML =
                                '<div class="text-center py-8 text-red-400">Error loading data</div>';
                        });
                });
            });
        }

        function resetFilters() {
            if (searchInput) searchInput.value = '';
            if (filterModul) filterModul.value = '';
            if (filterAktivitas) filterAktivitas.value = '';
            if (tanggalAwal) tanggalAwal.value = '';
            if (tanggalAkhir) tanggalAkhir.value = '';
            fetchLogs();
        }

        function viewLogDetail(id) {
            const modal = document.getElementById('logModal');
            const content = document.getElementById('logDetailContent');
            content.innerHTML =
                '<div class="text-center py-8"><div class="loading mx-auto"></div><p class="text-gray-400 mt-2">Loading...</p></div>';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            fetch(`/activity-logs/${id}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        renderLogDetail(data.data);
                    } else {
                        content.innerHTML = '<div class="text-center py-8 text-red-400">Error loading data</div>';
                    }
                })
                .catch(error => {
                    content.innerHTML = '<div class="text-center py-8 text-red-400">Error loading data</div>';
                });
        }

        function renderLogDetail(log) {
            const content = document.getElementById('logDetailContent');
            const formatJSON = (data) => {
                if (!data) return '<span class="json-null">null</span>';
                return JSON.stringify(data, null, 2)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(
                        /("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g,
                        function(match) {
                            let cls = 'json-number';
                            if (/^"/.test(match)) cls = /:$/.test(match) ? 'json-key' : 'json-string';
                            else if (/true|false/.test(match)) cls = 'json-boolean';
                            else if (/null/.test(match)) cls = 'json-null';
                            return '<span class="' + cls + '">' + match + '</span>';
                        });
            };

            content.innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-xs font-medium text-gray-400 mb-1">ID Log</label><p class="text-white">#${log.id}</p></div>
                        <div><label class="block text-xs font-medium text-gray-400 mb-1">User</label><p class="text-white">${log.user ? log.user.nama : 'System'} <span class="text-gray-400 text-xs">(${log.user ? log.user.username : '-'})</span></p></div>
                        <div><label class="block text-xs font-medium text-gray-400 mb-1">Aktivitas</label><p class="text-white">${log.aktivitas}</p></div>
                        <div><label class="block text-xs font-medium text-gray-400 mb-1">Modul</label><p class="text-white">${log.modul}</p></div>
                        <div><label class="block text-xs font-medium text-gray-400 mb-1">Data ID</label><p class="text-white">${log.data_id || '-'}</p></div>
                        <div><label class="block text-xs font-medium text-gray-400 mb-1">Tanggal</label><p class="text-white">${new Date(log.tanggal_aktivitas).toLocaleString('id-ID')}</p></div>
                    </div>
                    <div><label class="block text-xs font-medium text-gray-400 mb-1">Deskripsi</label><div class="bg-white/5 border border-white/10 rounded-lg p-3 text-white">${log.deskripsi}</div></div>
                    ${log.data_sebelum ? `<div><label class="block text-xs font-medium text-gray-400 mb-1">Data Sebelum</label><pre class="json-viewer">${formatJSON(log.data_sebelum)}</pre></div>` : ''}
                    ${log.data_sesudah ? `<div><label class="block text-xs font-medium text-gray-400 mb-1">Data Sesudah</label><pre class="json-viewer">${formatJSON(log.data_sesudah)}</pre></div>` : ''}
                    <div class="border-t border-white/10 pt-4 mt-4"><div class="flex gap-6 text-xs text-gray-400"><div>Dibuat: ${new Date(log.created_at).toLocaleString('id-ID')}</div><div>Diupdate: ${new Date(log.updated_at).toLocaleString('id-ID')}</div></div></div>
                </div>
            `;
        }

        function closeLogModal() {
            document.getElementById('logModal').classList.add('hidden');
            document.getElementById('logModal').classList.remove('flex');
        }

        // Event listeners
        if (searchInput) {
            searchInput.addEventListener('keyup', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(fetchLogs, 500);
            });
        }

        [filterModul, filterAktivitas, tanggalAwal, tanggalAkhir].forEach(filter => {
            if (filter) filter.addEventListener('change', fetchLogs);
        });

        if (tanggalAwal) {
            tanggalAwal.addEventListener('change', function() {
                if (tanggalAkhir) {
                    tanggalAkhir.min = this.value;
                    if (tanggalAkhir.value && tanggalAkhir.value < this.value) {
                        tanggalAkhir.value = this.value;
                    }
                }
            });
        }

        window.addEventListener('click', (e) => {
            if (e.target === document.getElementById('logModal')) closeLogModal();
            if (e.target === document.getElementById('pdfModal')) closePdfModal();
        });

        document.addEventListener('DOMContentLoaded', () => {
            loadStatistics();
            attachPaginationListeners();
        });
    </script>
@endpush
