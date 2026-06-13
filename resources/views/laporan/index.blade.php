<!-- resources/views/laporan/index.blade.php -->

@extends('layouts.app')

@section('title', 'Data Laporan - E-Ticketing System')

@section('content')
    <div class="flex min-h-screen bg-[#001D39]">
        <!-- Include sidebar -->
        @include('layouts.sidebar')

        <!-- Konten halaman -->
        <div class="flex-1 p-3 sm:p-4 md:p-6 overflow-y-auto" style="height: 100vh;">
            <!-- Header -->
            <div class="mb-4 sm:mb-6 md:mb-8">
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-white mb-1 sm:mb-2">Data Laporan</h1>
                <p class="text-xs sm:text-sm text-gray-400">Kelola laporan tiket dalam sistem</p>
            </div>

            <!-- Filter Section - Responsive -->
            <div class="mb-4 sm:mb-6">
                <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl p-3 sm:p-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3">
                        <!-- Search -->
                        <div class="sm:col-span-2 lg:col-span-1">
                            <label class="block text-[10px] sm:text-xs font-medium text-gray-300 mb-1">Pencarian</label>
                            <div class="relative">
                                <input type="text" placeholder="Cari nomor tiket, nama, kantor..."
                                    class="w-full bg-white/5 border border-white/10 text-gray-300 rounded-lg pl-8 pr-3 py-1.5 sm:py-2 text-[11px] sm:text-sm focus:border-blue-400 focus:outline-none transition-all"
                                    id="searchInput" value="{{ request('search') }}">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 absolute left-2.5 top-2 sm:top-2.5"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Filter Kantor -->
                        <div>
                            <label for="filterKantor"
                                class="block text-[10px] sm:text-xs font-medium text-gray-300 mb-1">Kantor</label>
                            <select id="filterKantor"
                                class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-2 sm:px-3 py-1.5 sm:py-2 text-[11px] sm:text-sm focus:border-blue-400 focus:outline-none transition-all">
                                <option value="" class="bg-[#001D39] text-gray-400">Semua</option>
                                @foreach ($kantors as $kantor)
                                    <option value="{{ $kantor->id }}" class="bg-[#001D39] text-white"
                                        {{ request('kantor') == $kantor->id ? 'selected' : '' }}>
                                        {{ $kantor->nama_cabang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Aplikasi -->
                        <div>
                            <label for="filterAplikasi"
                                class="block text-[10px] sm:text-xs font-medium text-gray-300 mb-1">Aplikasi</label>
                            <select id="filterAplikasi"
                                class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-2 sm:px-3 py-1.5 sm:py-2 text-[11px] sm:text-sm focus:border-blue-400 focus:outline-none transition-all">
                                <option value="" class="bg-[#001D39] text-gray-400">Semua</option>
                                @foreach ($jenisAplikasis as $aplikasi)
                                    <option value="{{ $aplikasi->id }}" class="bg-[#001D39] text-white"
                                        {{ request('aplikasi') == $aplikasi->id ? 'selected' : '' }}>
                                        {{ $aplikasi->jenis_aplikasi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Status -->
                        <div>
                            <label for="filterStatus"
                                class="block text-[10px] sm:text-xs font-medium text-gray-300 mb-1">Status</label>
                            <select id="filterStatus"
                                class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-2 sm:px-3 py-1.5 sm:py-2 text-[11px] sm:text-sm focus:border-blue-400 focus:outline-none transition-all">
                                <option value="" class="bg-[#001D39] text-gray-400">Semua</option>
                                <option value="open" class="bg-[#001D39] text-yellow-400">Open</option>
                                <option value="process" class="bg-[#001D39] text-blue-400">Process</option>
                                <option value="done" class="bg-[#001D39] text-green-400">Done</option>
                                <option value="reject" class="bg-[#001D39] text-red-400">Reject</option>
                                <option value="pending" class="bg-[#001D39] text-orange-400">Pending</option>
                                <option value="escalate" class="bg-[#001D39] text-purple-400">Escalate</option>
                            </select>
                        </div>

                        <!-- Filter Tanggal Awal -->
                        <div>
                            <label for="tanggalAwal" class="block text-[10px] sm:text-xs font-medium text-gray-300 mb-1">Tgl
                                Awal</label>
                            <input type="date" id="tanggalAwal" value="{{ request('tanggal_awal') }}"
                                class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-2 sm:px-3 py-1.5 sm:py-2 text-[11px] sm:text-sm focus:border-blue-400 focus:outline-none transition-all date-input-white">
                        </div>

                        <!-- Filter Tanggal Akhir -->
                        <div>
                            <label for="tanggalAkhir"
                                class="block text-[10px] sm:text-xs font-medium text-gray-300 mb-1">Tgl Akhir</label>
                            <input type="date" id="tanggalAkhir" value="{{ request('tanggal_akhir') }}"
                                class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-2 sm:px-3 py-1.5 sm:py-2 text-[11px] sm:text-sm focus:border-blue-400 focus:outline-none transition-all date-input-white">
                        </div>

                        <!-- Tombol PDF -->
                        <div>
                            <label class="block text-[10px] sm:text-xs font-medium text-gray-300 mb-1 invisible">PDF</label>
                            <button onclick="openPdfModal()"
                                class="w-full bg-red-600 hover:bg-red-700 text-white rounded-lg px-2 sm:px-3 py-1.5 sm:py-2 text-[11px] sm:text-sm transition-all transform hover:scale-[1.02] flex items-center justify-center gap-1 font-medium">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                PDF
                            </button>
                        </div>

                        <!-- Tombol Excel - Hanya tampil jika punya permission excel -->
                        @if (isset($permissions['can_excel']) && $permissions['can_excel'])
                            <div>
                                <label
                                    class="block text-[10px] sm:text-xs font-medium text-gray-300 mb-1 invisible">Excel</label>
                                <button onclick="openExcelModal()"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white rounded-lg px-2 sm:px-3 py-1.5 sm:py-2 text-[11px] sm:text-sm transition-all transform hover:scale-[1.02] flex items-center justify-center gap-1 font-medium">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Excel
                                </button>
                            </div>
                        @endif

                        <!-- Reset Filter Button -->
                        <div>
                            <label
                                class="block text-[10px] sm:text-xs font-medium text-gray-300 mb-1 invisible">Reset</label>
                            <button onclick="resetFilters()"
                                class="w-full bg-white/5 hover:bg-white/10 border border-white/10 text-gray-300 rounded-lg px-2 sm:px-3 py-1.5 sm:py-2 text-[11px] sm:text-sm transition-colors flex items-center justify-center gap-1">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alert Success -->
            @if (session('success'))
                <div
                    class="mb-3 sm:mb-4 bg-green-500/20 border border-green-500/50 text-green-400 px-3 sm:px-4 py-2 sm:py-3 rounded-lg text-xs sm:text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Alert Error -->
            @if (session('error'))
                <div
                    class="mb-3 sm:mb-4 bg-red-500/20 border border-red-500/50 text-red-400 px-3 sm:px-4 py-2 sm:py-3 rounded-lg text-xs sm:text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Table Container -->
            <div id="table-container">
                @include('laporan.table')
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal - Responsive -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-[#001D39] border border-white/10 rounded-xl sm:rounded-2xl w-full max-w-sm sm:max-w-md p-4 sm:p-6">
            <div class="text-center">
                <div
                    class="w-12 h-12 sm:w-16 sm:h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-white mb-2">Konfirmasi Hapus</h3>
                <p class="text-gray-400 text-xs sm:text-sm mb-4 sm:mb-6">Apakah Anda yakin ingin menghapus laporan <span
                        id="deleteLaporanInfo" class="text-white font-semibold"></span>?</p>

                <form id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="deleteLaporanId" name="laporanId">

                    <div class="flex gap-2 sm:gap-3">
                        <button type="button" onclick="closeDeleteModal()"
                            class="flex-1 px-3 sm:px-4 py-2 sm:py-2.5 border border-white/10 text-gray-300 rounded-lg hover:bg-white/5 transition-colors text-xs sm:text-sm">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg transition-colors text-xs sm:text-sm">
                            Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Status Update Modal - Responsive -->
    <div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div
            class="bg-[#001D39] border border-white/10 rounded-xl sm:rounded-2xl w-full max-w-sm sm:max-w-md p-4 sm:p-6 max-h-[85vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4 sm:mb-6">
                <h3 class="text-lg sm:text-xl font-semibold text-white">Update Status</h3>
                <button onclick="closeStatusModal()" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="statusForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="statusLaporanId" name="laporanId">

                <!-- Pilih Status -->
                <div class="mb-4 relative">
                    <label for="status" class="block text-xs sm:text-sm font-medium text-gray-300 mb-2">Status</label>
                    <select id="status" name="status"
                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-xs sm:text-sm focus:border-blue-400 focus:outline-none transition-all appearance-none cursor-pointer">
                        <option value="open" class="bg-[#001D39] text-yellow-400">Open</option>
                        <option value="process" class="bg-[#001D39] text-blue-400">Process</option>
                        <option value="done" class="bg-[#001D39] text-green-400">Done</option>
                        <option value="reject" class="bg-[#001D39] text-red-400">Reject</option>
                        <option value="pending" class="bg-[#001D39] text-orange-400">Pending</option>
                        <option value="escalate" class="bg-[#001D39] text-purple-400">Escalate</option>
                    </select>
                    <div
                        class="pointer-events-none absolute right-2 sm:right-3 bottom-2 sm:bottom-2.5 flex items-center text-gray-400">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </div>
                </div>

                <!-- Deskripsi untuk Pending -->
                <div id="pendingDeskripsiContainer" class="mb-4 hidden">
                    <label for="pending_deskripsi" class="block text-xs sm:text-sm font-medium text-orange-400 mb-2">
                        Alasan Pending <span class="text-red-400">*</span>
                    </label>
                    <textarea id="pending_deskripsi" name="pending_deskripsi" rows="2"
                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-3 sm:px-4 py-2 text-xs sm:text-sm focus:border-orange-400 focus:outline-none transition-colors"
                        placeholder="Masukkan alasan pending..."></textarea>
                </div>

                <!-- Deskripsi untuk Escalate -->
                <div id="escalateDeskripsiContainer" class="mb-4 hidden">
                    <label for="escalate_deskripsi" class="block text-xs sm:text-sm font-medium text-purple-400 mb-2">
                        Alasan Escalate <span class="text-red-400">*</span>
                    </label>
                    <textarea id="escalate_deskripsi" name="escalate_deskripsi" rows="2"
                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-3 sm:px-4 py-2 text-xs sm:text-sm focus:border-purple-400 focus:outline-none transition-colors"
                        placeholder="Masukkan alasan escalate..."></textarea>
                </div>

                <!-- Deskripsi untuk Done (Solusi) -->
                <div id="doneDeskripsiContainer" class="mb-4 hidden">
                    <label for="solusi" class="block text-xs sm:text-sm font-medium text-green-400 mb-2">
                        Solusi <span class="text-red-400">*</span>
                    </label>
                    <textarea id="solusi" name="solusi" rows="2"
                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-3 sm:px-4 py-2 text-xs sm:text-sm focus:border-green-400 focus:outline-none transition-colors"
                        placeholder="Masukkan solusi..."></textarea>
                </div>

                <!-- Tanggal Selesai untuk Done -->
                <div id="tanggalSelesaiContainer" class="mb-4 hidden">
                    <label for="tanggal_selesai_modal" class="block text-xs sm:text-sm font-medium text-gray-300 mb-2">
                        Tanggal Selesai (WIT)
                    </label>
                    <input type="datetime-local" id="tanggal_selesai_modal" name="tanggal_selesai"
                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-xs sm:text-sm focus:border-green-400 focus:outline-none transition-all cursor-not-allowed opacity-70"
                        readonly disabled>
                    <p class="text-[10px] sm:text-xs text-yellow-400 mt-1">Waktu WIT (UTC+9)</p>
                </div>

                <div class="flex gap-2 sm:gap-3 mt-4 sm:mt-6">
                    <button type="button" onclick="closeStatusModal()"
                        class="flex-1 px-3 sm:px-4 py-2 sm:py-2.5 border border-white/10 text-gray-300 rounded-lg hover:bg-white/5 transition-colors text-xs sm:text-sm">
                        Batal
                    </button>
                    <button type="submit" id="submitStatusBtn"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg transition-colors text-xs sm:text-sm">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Download PDF Modal - Responsive with Info Notice -->
    <div id="pdfModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-[#001D39] border border-white/10 rounded-xl sm:rounded-2xl w-full max-w-sm sm:max-w-md p-4 sm:p-6">
            <div class="flex justify-between items-center mb-4 sm:mb-6">
                <h3 class="text-lg sm:text-xl font-semibold text-white">Pilih Orientasi PDF</h3>
                <button onclick="closePdfModal()" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Informasi Export PDF -->
            <div class="mb-4 sm:mb-6">
                <div class="bg-blue-500/20 border border-blue-500/50 rounded-lg p-3 sm:p-4 mb-4">
                    <div class="flex items-center gap-2 text-blue-400 mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">Informasi Export PDF</span>
                    </div>
                    <p class="text-gray-300 text-xs sm:text-sm">
                        Data akan diexport ke file PDF dengan format yang sudah ditentukan.
                        Proses export akan mengikuti filter yang sedang aktif.
                    </p>
                </div>

                <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-3">
                    <p class="text-yellow-400 text-xs sm:text-sm flex items-start gap-2">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        <span>Pastikan filter sudah sesuai sebelum mendownload PDF.</span>
                    </p>
                </div>
            </div>

            <form id="pdfForm" action="{{ route('laporan.pdf') }}" method="GET" target="_blank">
                <!-- Hidden inputs untuk menyimpan filter saat ini -->
                <input type="hidden" name="search" id="pdfSearch" value="{{ request('search') }}">
                <input type="hidden" name="kantor" id="pdfKantor" value="{{ request('kantor') }}">
                <input type="hidden" name="aplikasi" id="pdfAplikasi" value="{{ request('aplikasi') }}">
                <input type="hidden" name="status" id="pdfStatus" value="{{ request('status') }}">
                <input type="hidden" name="tanggal_awal" id="pdfTanggalAwal" value="{{ request('tanggal_awal') }}">
                <input type="hidden" name="tanggal_akhir" id="pdfTanggalAkhir" value="{{ request('tanggal_akhir') }}">

                <div class="mb-4 sm:mb-6">
                    <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-2 sm:mb-3">Orientasi
                        Halaman</label>
                    <div class="grid grid-cols-2 gap-3 sm:gap-4">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="orientation" value="portrait" class="sr-only peer" checked>
                            <div
                                class="p-2 sm:p-3 bg-white/5 border border-white/10 rounded-lg sm:rounded-xl text-center hover:bg-white/10 peer-checked:border-blue-400 peer-checked:bg-blue-400/10 transition-all">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 mx-auto text-gray-400 mb-1 sm:mb-2" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <rect x="4" y="4" width="16" height="20" rx="2" stroke="currentColor"
                                        fill="none" stroke-width="1.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 8h8M8 12h8M8 16h4" />
                                </svg>
                                <span class="text-white text-xs sm:text-sm font-medium">Portrait</span>
                            </div>
                        </label>

                        <label class="relative cursor-pointer">
                            <input type="radio" name="orientation" value="landscape" class="sr-only peer">
                            <div
                                class="p-2 sm:p-3 bg-white/5 border border-white/10 rounded-lg sm:rounded-xl text-center hover:bg-white/10 peer-checked:border-blue-400 peer-checked:bg-blue-400/10 transition-all">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 mx-auto text-gray-400 mb-1 sm:mb-2" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <rect x="2" y="4" width="20" height="16" rx="2" stroke="currentColor"
                                        fill="none" stroke-width="1.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 8h12M6 12h12M6 16h8" />
                                </svg>
                                <span class="text-white text-xs sm:text-sm font-medium">Landscape</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="flex gap-2 sm:gap-3">
                    <button type="button" onclick="closePdfModal()"
                        class="flex-1 px-3 sm:px-4 py-2 sm:py-2.5 border border-white/10 text-gray-300 rounded-lg hover:bg-white/5 transition-colors text-xs sm:text-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg transition-all transform hover:scale-[1.02] flex items-center justify-center gap-1 sm:gap-2 text-xs sm:text-sm">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="excelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-[#001D39] border border-white/10 rounded-xl sm:rounded-2xl w-full max-w-sm sm:max-w-md p-4 sm:p-6">
            <div class="flex justify-between items-center mb-4 sm:mb-6">
                <h3 class="text-lg sm:text-xl font-semibold text-white">Konfirmasi Export Excel</h3>
                <button onclick="closeExcelModal()" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="mb-4 sm:mb-6">
                <div class="bg-green-500/20 border border-green-500/50 rounded-lg p-3 sm:p-4 mb-4">
                    <div class="flex items-center gap-2 text-green-400 mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">Informasi Export</span>
                    </div>
                    <p class="text-gray-300 text-xs sm:text-sm">
                        Data akan diexport ke file Excel (.xlsx) dengan format yang sudah ditentukan.
                        Proses export akan mengikuti filter yang sedang aktif.
                    </p>
                </div>

                <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-3">
                    <p class="text-yellow-400 text-xs sm:text-sm flex items-start gap-2">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        <span>Pastikan filter sudah sesuai sebelum mengexport data.</span>
                    </p>
                </div>
            </div>

            <form id="excelForm" action="{{ route('laporan.export-excel') }}" method="GET" target="_blank">
                <!-- Hidden inputs untuk menyimpan filter saat ini -->
                <input type="hidden" name="search" id="excelSearch" value="{{ request('search') }}">
                <input type="hidden" name="kantor" id="excelKantor" value="{{ request('kantor') }}">
                <input type="hidden" name="aplikasi" id="excelAplikasi" value="{{ request('aplikasi') }}">
                <input type="hidden" name="status" id="excelStatus" value="{{ request('status') }}">
                <input type="hidden" name="tanggal_awal" id="excelTanggalAwal" value="{{ request('tanggal_awal') }}">
                <input type="hidden" name="tanggal_akhir" id="excelTanggalAkhir"
                    value="{{ request('tanggal_akhir') }}">

                <div class="flex gap-2 sm:gap-3">
                    <button type="button" onclick="closeExcelModal()"
                        class="flex-1 px-3 sm:px-4 py-2 sm:py-2.5 border border-white/10 text-gray-300 rounded-lg hover:bg-white/5 transition-colors text-xs sm:text-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg transition-all transform hover:scale-[1.02] flex items-center justify-center gap-1 sm:gap-2 text-xs sm:text-sm">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        Export Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        .overflow-y-auto::-webkit-scrollbar {
            width: 4px;
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

        .date-input-white {
            color-scheme: dark;
        }

        .date-input-white::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }

        #deleteModal,
        #statusModal,
        #pdfModal {
            transition: opacity 0.3s ease;
        }

        /* Sembunyikan label invisible di mobile agar tidak makan space */
        .invisible {
            visibility: hidden;
        }

        @media (max-width: 640px) {
            .invisible {
                display: none;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        const filterKantor = document.getElementById('filterKantor');
        const filterAplikasi = document.getElementById('filterAplikasi');
        const filterStatus = document.getElementById('filterStatus');
        const tanggalAwal = document.getElementById('tanggalAwal');
        const tanggalAkhir = document.getElementById('tanggalAkhir');
        const tableContainer = document.getElementById('table-container');

        function getCurrentDateTimeWIT() {
            const now = new Date();
            const witTime = new Date(now.getTime() + (9 * 60 * 60 * 1000));
            const year = witTime.getUTCFullYear();
            const month = String(witTime.getUTCMonth() + 1).padStart(2, '0');
            const day = String(witTime.getUTCDate()).padStart(2, '0');
            const hours = String(witTime.getUTCHours()).padStart(2, '0');
            const minutes = String(witTime.getUTCMinutes()).padStart(2, '0');
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }

        // Delete functions
        function openDeleteModal(id, nomorTicket, namaPelapor) {
            document.getElementById('deleteLaporanId').value = id;
            document.getElementById('deleteLaporanInfo').textContent = nomorTicket + ' - ' + namaPelapor;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }

        document.getElementById('deleteForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const laporanId = document.getElementById('deleteLaporanId').value;
            fetch(`/laporan/${laporanId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content'),
                    'Accept': 'application/json'
                }
            }).then(response => response.json()).then(data => {
                if (data.status === 'success') {
                    closeDeleteModal();
                    window.location.reload();
                } else alert(data.message || 'Gagal menghapus data');
            }).catch(error => alert('Terjadi kesalahan'));
        });

        // Status functions
        function openStatusModal(id, currentStatus) {
            document.getElementById('statusLaporanId').value = id;
            document.getElementById('status').value = currentStatus;
            document.getElementById('pendingDeskripsiContainer').classList.add('hidden');
            document.getElementById('escalateDeskripsiContainer').classList.add('hidden');
            document.getElementById('doneDeskripsiContainer').classList.add('hidden');
            document.getElementById('tanggalSelesaiContainer').classList.add('hidden');
            document.getElementById('pending_deskripsi').value = '';
            document.getElementById('escalate_deskripsi').value = '';
            document.getElementById('solusi').value = '';
            document.getElementById('tanggal_selesai_modal').value = getCurrentDateTimeWIT();
            toggleStatusFields(currentStatus);
            document.getElementById('statusModal').classList.remove('hidden');
            document.getElementById('statusModal').classList.add('flex');
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
            document.getElementById('statusModal').classList.remove('flex');
        }

        function toggleStatusFields(status) {
            const pendingContainer = document.getElementById('pendingDeskripsiContainer');
            const escalateContainer = document.getElementById('escalateDeskripsiContainer');
            const doneContainer = document.getElementById('doneDeskripsiContainer');
            const tanggalContainer = document.getElementById('tanggalSelesaiContainer');
            pendingContainer.classList.add('hidden');
            escalateContainer.classList.add('hidden');
            doneContainer.classList.add('hidden');
            tanggalContainer.classList.add('hidden');
            if (status === 'pending') pendingContainer.classList.remove('hidden');
            else if (status === 'escalate') escalateContainer.classList.remove('hidden');
            else if (status === 'done') {
                doneContainer.classList.remove('hidden');
                tanggalContainer.classList.remove('hidden');
            }
        }
        document.getElementById('status')?.addEventListener('change', function() {
            toggleStatusFields(this.value);
        });

        document.getElementById('statusForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const laporanId = document.getElementById('statusLaporanId').value;
            const status = document.getElementById('status').value;
            const formData = {
                status: status,
                _method: 'PUT',
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };
            if (status === 'pending') {
                const desc = document.getElementById('pending_deskripsi').value.trim();
                if (!desc) return Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Alasan pending harus diisi!'
                });
                formData.pending_deskripsi = desc;
            } else if (status === 'escalate') {
                const desc = document.getElementById('escalate_deskripsi').value.trim();
                if (!desc) return Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Alasan escalate harus diisi!'
                });
                formData.escalate_deskripsi = desc;
            } else if (status === 'done') {
                const solusi = document.getElementById('solusi').value.trim();
                if (!solusi) return Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Solusi harus diisi!'
                });
                formData.solusi = solusi;
                const tanggalSelesai = document.getElementById('tanggal_selesai_modal').value;
                if (tanggalSelesai) formData.tanggal_selesai = tanggalSelesai.replace('T', ' ') + ':00';
            }
            Swal.fire({
                title: 'Memproses...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            fetch(`/laporan/${laporanId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            }).then(response => response.json()).then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Status berhasil diperbarui',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        closeStatusModal();
                        window.location.reload();
                    });
                } else Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message || 'Gagal mengupdate status'
                });
            }).catch(error => Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan'
            }));
        });

        // PDF Modal
        function openPdfModal() {
            document.getElementById('pdfSearch').value = searchInput?.value || '';
            document.getElementById('pdfKantor').value = filterKantor?.value || '';
            document.getElementById('pdfAplikasi').value = filterAplikasi?.value || '';
            document.getElementById('pdfStatus').value = filterStatus?.value || '';
            document.getElementById('pdfTanggalAwal').value = tanggalAwal?.value || '';
            document.getElementById('pdfTanggalAkhir').value = tanggalAkhir?.value || '';
            document.getElementById('pdfModal').classList.remove('hidden');
            document.getElementById('pdfModal').classList.add('flex');
        }

        function closePdfModal() {
            document.getElementById('pdfModal').classList.add('hidden');
            document.getElementById('pdfModal').classList.remove('flex');
        }

        // Excel Modal functions
        function openExcelModal() {
            document.getElementById('excelSearch').value = searchInput?.value || '';
            document.getElementById('excelKantor').value = filterKantor?.value || '';
            document.getElementById('excelAplikasi').value = filterAplikasi?.value || '';
            document.getElementById('excelStatus').value = filterStatus?.value || '';
            document.getElementById('excelTanggalAwal').value = tanggalAwal?.value || '';
            document.getElementById('excelTanggalAkhir').value = tanggalAkhir?.value || '';
            document.getElementById('excelModal').classList.remove('hidden');
            document.getElementById('excelModal').classList.add('flex');
        }

        function closeExcelModal() {
            document.getElementById('excelModal').classList.add('hidden');
            document.getElementById('excelModal').classList.remove('flex');
        }

        // Update window click event untuk menutup excel modal
        window.addEventListener('click', function(e) {
            if (e.target === document.getElementById('deleteModal')) closeDeleteModal();
            if (e.target === document.getElementById('statusModal')) closeStatusModal();
            if (e.target === document.getElementById('pdfModal')) closePdfModal();
            if (e.target === document.getElementById('excelModal')) closeExcelModal(); // Tambahkan ini
        });

        // WhatsApp
        function sendWhatsApp(laporanId) {
            Swal.fire({
                title: 'Memproses...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            fetch(`/laporan/${laporanId}/whatsapp`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            }).then(response => response.json()).then(data => {
                if (data.status === 'success') {
                    Swal.close();
                    window.open(`https://wa.me/${data.phone_number}?text=${encodeURIComponent(data.message)}`,
                        '_blank');
                } else Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message
                });
            }).catch(error => Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan'
            }));
        }

        // Filter functions
        function fetchLaporans() {
            if (tanggalAwal?.value && tanggalAkhir?.value && tanggalAwal.value > tanggalAkhir.value) return alert(
                'Tanggal akhir harus >= tanggal awal');
            tableContainer.innerHTML =
                '<div class="text-center py-8"><div class="loading mx-auto"></div><p class="text-gray-400 mt-2">Loading...</p></div>';
            const url = new URL(window.location.href);
            if (searchInput) url.searchParams.set('search', searchInput.value);
            if (filterKantor) url.searchParams.set('kantor', filterKantor.value);
            if (filterAplikasi) url.searchParams.set('aplikasi', filterAplikasi.value);
            if (filterStatus) url.searchParams.set('status', filterStatus.value);
            if (tanggalAwal) url.searchParams.set('tanggal_awal', tanggalAwal.value);
            if (tanggalAkhir) url.searchParams.set('tanggal_akhir', tanggalAkhir.value);
            window.history.pushState({}, '', url);
            fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text()).then(html => {
                    tableContainer.innerHTML = html;
                    attachPaginationListeners();
                })
                .catch(() => tableContainer.innerHTML =
                    '<div class="text-center py-8 text-red-400">Error loading data</div>');
        }

        function attachPaginationListeners() {
            document.querySelectorAll('.pagination-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = new URL(this.href);
                    if (searchInput) url.searchParams.set('search', searchInput.value);
                    if (filterKantor) url.searchParams.set('kantor', filterKantor.value);
                    if (filterAplikasi) url.searchParams.set('aplikasi', filterAplikasi.value);
                    if (filterStatus) url.searchParams.set('status', filterStatus.value);
                    if (tanggalAwal) url.searchParams.set('tanggal_awal', tanggalAwal.value);
                    if (tanggalAkhir) url.searchParams.set('tanggal_akhir', tanggalAkhir.value);
                    tableContainer.innerHTML =
                        '<div class="text-center py-8"><div class="loading mx-auto"></div><p class="text-gray-400 mt-2">Loading...</p></div>';
                    fetch(url.toString(), {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.text()).then(html => {
                            tableContainer.innerHTML = html;
                            attachPaginationListeners();
                        })
                        .catch(() => tableContainer.innerHTML =
                            '<div class="text-center py-8 text-red-400">Error loading data</div>');
                });
            });
        }

        function resetFilters() {
            if (searchInput) searchInput.value = '';
            if (filterKantor) filterKantor.value = '';
            if (filterAplikasi) filterAplikasi.value = '';
            if (filterStatus) filterStatus.value = '';
            if (tanggalAwal) tanggalAwal.value = '';
            if (tanggalAkhir) tanggalAkhir.value = '';
            fetchLaporans();
        }

        if (searchInput) searchInput.addEventListener('keyup', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(fetchLaporans, 500);
        });
        [filterKantor, filterAplikasi, filterStatus, tanggalAwal, tanggalAkhir].forEach(filter => {
            if (filter) filter.addEventListener('change', fetchLaporans);
        });
        if (tanggalAwal) tanggalAwal.addEventListener('change', function() {
            if (tanggalAkhir) tanggalAkhir.min = this.value;
        });
        attachPaginationListeners();

        window.addEventListener('click', function(e) {
            if (e.target === document.getElementById('deleteModal')) closeDeleteModal();
            if (e.target === document.getElementById('statusModal')) closeStatusModal();
            if (e.target === document.getElementById('pdfModal')) closePdfModal();
        });
    </script>
@endpush
