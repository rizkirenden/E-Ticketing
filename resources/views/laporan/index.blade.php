<!-- resources/views/laporan/index.blade.php -->

@extends('layouts.app')

@section('title', 'Data Laporan - E-Ticketing System')

@section('content')
    <div class="flex min-h-screen bg-[#001D39]">
        <!-- Include sidebar - sudah fixed dari sisi component -->
        @include('layouts.sidebar')

        <!-- Konten halaman dengan margin left selebar sidebar dan bisa discroll -->
        <div class="flex-1 p-6 overflow-y-auto" style="height: 100vh;">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Data Laporan</h1>
                <p class="text-gray-400">Kelola laporan tiket dalam sistem</p>
            </div>

            <!-- Filter Section - Enhanced like detail_laporan -->
            <div class="mb-6">
                <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl p-4">
                    <div class="grid grid-cols-1 md:grid-cols-8 gap-3 items-end">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-300 mb-1">Pencarian</label>
                            <div class="relative">
                                <input type="text" placeholder="Cari nomor tiket, nama, kantor, aplikasi..."
                                    class="w-full bg-white/5 border border-white/10 text-gray-300 rounded-lg pl-10 pr-3 py-2 text-sm focus:border-blue-400 focus:outline-none transition-all"
                                    id="searchInput" value="{{ request('search') }}">
                                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Filter Kantor -->
                        <div>
                            <label for="filterKantor" class="block text-xs font-medium text-gray-300 mb-1">Kantor</label>
                            <select id="filterKantor"
                                class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-3 py-2 text-sm focus:border-blue-400 focus:outline-none transition-all">
                                <option value="" class="bg-[#001D39] text-gray-400">Semua Kantor</option>
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
                                class="block text-xs font-medium text-gray-300 mb-1">Aplikasi</label>
                            <select id="filterAplikasi"
                                class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-3 py-2 text-sm focus:border-blue-400 focus:outline-none transition-all">
                                <option value="" class="bg-[#001D39] text-gray-400">Semua Aplikasi</option>
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
                            <label for="filterStatus" class="block text-xs font-medium text-gray-300 mb-1">Status</label>
                            <select id="filterStatus"
                                class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-3 py-2 text-sm focus:border-blue-400 focus:outline-none transition-all">
                                <option value="" class="bg-[#001D39] text-gray-400">Semua Status</option>
                                <option value="open" class="bg-[#001D39] text-yellow-400"
                                    {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="process" class="bg-[#001D39] text-blue-400"
                                    {{ request('status') == 'process' ? 'selected' : '' }}>Process</option>
                                <option value="done" class="bg-[#001D39] text-green-400"
                                    {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
                                <option value="reject" class="bg-[#001D39] text-red-400"
                                    {{ request('status') == 'reject' ? 'selected' : '' }}>Reject</option>
                                <option value="pending" class="bg-[#001D39] text-orange-400"
                                    {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="escalate" class="bg-[#001D39] text-purple-400"
                                    {{ request('status') == 'escalate' ? 'selected' : '' }}>Escalate</option>
                            </select>
                        </div>

                        <!-- Filter Tanggal Awal -->
                        <div>
                            <label for="tanggalAwal" class="block text-xs font-medium text-gray-300 mb-1">Tanggal
                                Awal</label>
                            <input type="date" id="tanggalAwal" value="{{ request('tanggal_awal') }}"
                                class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-3 py-2 text-sm focus:border-blue-400 focus:outline-none transition-all date-input-white">
                        </div>

                        <!-- Filter Tanggal Akhir -->
                        <div>
                            <label for="tanggalAkhir" class="block text-xs font-medium text-gray-300 mb-1">Tanggal
                                Akhir</label>
                            <input type="date" id="tanggalAkhir" value="{{ request('tanggal_akhir') }}"
                                class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-3 py-2 text-sm focus:border-blue-400 focus:outline-none transition-all date-input-white">
                        </div>

                        <!--
            <div>
                <button onclick="openPdfModal()"
                    class="w-full bg-red-600 hover:bg-red-700 text-white rounded-lg px-3 py-2 text-sm transition-all transform hover:scale-[1.02] hover:shadow-xl flex items-center justify-center gap-1 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    PDF
                </button>
            </div>
            -->

                        <!-- Reset Filter Button -->
                        <div>
                            <button onclick="resetFilters()"
                                class="w-full bg-white/5 hover:bg-white/10 border border-white/10 text-gray-300 rounded-lg px-3 py-2 text-sm transition-colors flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="mb-4 bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Alert Error -->
            @if (session('error'))
                <div class="mb-4 bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Table Container untuk live search dan filter -->
            <div id="table-container">
                @include('laporan.table')
            </div>
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
                <p class="text-gray-400 mb-6">Apakah Anda yakin ingin menghapus laporan <span id="deleteLaporanInfo"
                        class="text-white font-semibold"></span>?</p>

                <form id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="deleteLaporanId" name="laporanId">

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

    <!-- Status Update Modal dengan Deskripsi dan Datetime-local Readonly (WIT) -->
    <div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-[#001D39] border border-white/10 rounded-2xl w-full max-w-md p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-white">Update Status Laporan</h3>
                <button onclick="closeStatusModal()" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <label for="status" class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                    <select id="status" name="status"
                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 focus:border-blue-400 focus:outline-none transition-colors appearance-none cursor-pointer">
                        <option value="open" class="bg-[#001D39] text-yellow-400">Open</option>
                        <option value="process" class="bg-[#001D39] text-blue-400">Process</option>
                        <option value="done" class="bg-[#001D39] text-green-400">Done</option>
                        <option value="reject" class="bg-[#001D39] text-red-400">Reject</option>
                        <option value="pending" class="bg-[#001D39] text-orange-400">Pending</option>
                        <option value="escalate" class="bg-[#001D39] text-purple-400">Escalate</option>
                    </select>
                    <div class="pointer-events-none absolute right-3 bottom-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </div>
                </div>

                <!-- Deskripsi untuk Pending -->
                <div id="pendingDeskripsiContainer" class="mb-4 hidden">
                    <label for="pending_deskripsi" class="block text-sm font-medium text-orange-400 mb-2">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Alasan Pending <span class="text-red-400">*</span>
                    </label>
                    <textarea id="pending_deskripsi" name="pending_deskripsi" rows="3"
                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 focus:border-orange-400 focus:outline-none transition-colors"
                        placeholder="Masukkan alasan mengapa laporan ini dipending..."></textarea>
                </div>

                <!-- Deskripsi untuk Escalate -->
                <div id="escalateDeskripsiContainer" class="mb-4 hidden">
                    <label for="escalate_deskripsi" class="block text-sm font-medium text-purple-400 mb-2">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        Alasan Escalate <span class="text-red-400">*</span>
                    </label>
                    <textarea id="escalate_deskripsi" name="escalate_deskripsi" rows="3"
                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 focus:border-purple-400 focus:outline-none transition-colors"
                        placeholder="Masukkan alasan mengapa laporan ini di-escalate..."></textarea>
                </div>

                <!-- Deskripsi untuk Done (Solusi) -->
                <div id="doneDeskripsiContainer" class="mb-4 hidden">
                    <label for="solusi" class="block text-sm font-medium text-green-400 mb-2">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Solusi <span class="text-red-400">*</span>
                    </label>
                    <textarea id="solusi" name="solusi" rows="3"
                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 focus:border-green-400 focus:outline-none transition-colors"
                        placeholder="Masukkan solusi yang diberikan..."></textarea>
                </div>

                <!-- Tanggal Selesai untuk Done - READONLY dengan WIT (UTC+9) -->
                <div id="tanggalSelesaiContainer" class="mb-4 hidden">
                    <label for="tanggal_selesai_modal" class="block text-sm font-medium text-gray-300 mb-2">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Tanggal & Waktu Selesai (WIT)
                    </label>
                    <input type="datetime-local" id="tanggal_selesai_modal" name="tanggal_selesai"
                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 focus:border-green-400 focus:outline-none transition-colors cursor-not-allowed opacity-70"
                        readonly disabled>
                    <p class="text-xs text-yellow-400 mt-1">
                        <svg class="inline w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Waktu WIT (UTC+9) - Otomatis sesuai waktu server
                    </p>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeStatusModal()"
                        class="flex-1 px-4 py-2.5 border border-white/10 text-gray-300 rounded-lg hover:bg-white/5 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="submitStatusBtn"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg transition-colors">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Download PDF Modal -->
    <div id="pdfModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-[#001D39] border border-white/10 rounded-2xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-white">Pilih Orientasi PDF</h3>
                <button onclick="closePdfModal()" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="pdfForm" action="{{ route('laporan.pdf') }}" method="GET" target="_blank">
                <!-- Hidden inputs untuk menyimpan filter saat ini -->
                <input type="hidden" name="search" id="pdfSearch" value="{{ request('search') }}">
                <input type="hidden" name="kantor" id="pdfKantor" value="{{ request('kantor') }}">
                <input type="hidden" name="aplikasi" id="pdfAplikasi" value="{{ request('aplikasi') }}">
                <input type="hidden" name="status" id="pdfStatus" value="{{ request('status') }}">
                <input type="hidden" name="tanggal_awal" id="pdfTanggalAwal" value="{{ request('tanggal_awal') }}">
                <input type="hidden" name="tanggal_akhir" id="pdfTanggalAkhir" value="{{ request('tanggal_akhir') }}">

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-300 mb-3">Pilih Orientasi Halaman</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="orientation" value="portrait" class="sr-only peer" checked>
                            <div
                                class="p-4 bg-white/5 border border-white/10 rounded-xl text-center hover:bg-white/10 peer-checked:border-blue-400 peer-checked:bg-blue-400/10 transition-all">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                    <rect x="2" y="2" width="20" height="20" rx="2" stroke="currentColor"
                                        fill="none" stroke-width="1.5" />
                                </svg>
                                <span class="text-white font-medium">Portrait</span>
                                <span class="text-xs text-gray-400 block mt-1">Vertikal (A4)</span>
                            </div>
                        </label>

                        <label class="relative cursor-pointer">
                            <input type="radio" name="orientation" value="landscape" class="sr-only peer">
                            <div
                                class="p-4 bg-white/5 border border-white/10 rounded-xl text-center hover:bg-white/10 peer-checked:border-blue-400 peer-checked:bg-blue-400/10 transition-all">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                    <rect x="2" y="2" width="20" height="20" rx="2" stroke="currentColor"
                                        fill="none" stroke-width="1.5" transform="rotate(90 12 12)" />
                                </svg>
                                <span class="text-white font-medium">Landscape</span>
                                <span class="text-xs text-gray-400 block mt-1">Horizontal (A4)</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closePdfModal()"
                        class="flex-1 px-4 py-2.5 border border-white/10 text-gray-300 rounded-lg hover:bg-white/5 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white px-4 py-2.5 rounded-lg transition-all transform hover:scale-[1.02] hover:shadow-xl flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

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
        #deleteModal,
        #statusModal,
        #pdfModal {
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

        /* Styling untuk input date dengan ikon putih */
        .date-input-white {
            color-scheme: dark;
        }

        .date-input-white::-webkit-calendar-picker-indicator {
            filter: invert(1) brightness(100%);
            opacity: 1;
            cursor: pointer;
            background-color: transparent;
            padding: 4px;
            border-radius: 4px;
        }

        .date-input-white::-webkit-calendar-picker-indicator:hover {
            opacity: 0.8;
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Styling untuk datetime-local input readonly */
        input[type="datetime-local"]:read-only,
        input[type="datetime-local"]:disabled {
            color-scheme: dark;
            cursor: not-allowed;
            opacity: 0.7;
        }

        input[type="datetime-local"]:read-only::-webkit-calendar-picker-indicator,
        input[type="datetime-local"]:disabled::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: not-allowed;
            opacity: 0.5;
        }

        /* Untuk Firefox */
        .date-input-white::-moz-calendar-picker-indicator {
            filter: invert(1);
            opacity: 1;
        }

        /* Styling untuk tabel agar responsif */
        #table-container {
            width: 100%;
            overflow-x: auto;
        }

        /* Memastikan card filter tidak overflow */
        .bg-white\/5 {
            width: 100%;
        }
    </style>
@endpush

@push('scripts')
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let currentStatusValue = '';
        let searchTimeout;

        // DOM Elements
        const searchInput = document.getElementById('searchInput');
        const filterKantor = document.getElementById('filterKantor');
        const filterAplikasi = document.getElementById('filterAplikasi');
        const filterStatus = document.getElementById('filterStatus');
        const tanggalAwal = document.getElementById('tanggalAwal');
        const tanggalAkhir = document.getElementById('tanggalAkhir');
        const tableContainer = document.getElementById('table-container');

        // ===================================================
        // HELPER FUNCTIONS - WIT Timezone (UTC+9)
        // ===================================================

        // Get current datetime in WIT (UTC+9) for datetime-local format
        function getCurrentDateTimeWIT() {
            // Create date in UTC then add 9 hours for WIT
            const now = new Date();
            const witTime = new Date(now.getTime() + (9 * 60 * 60 * 1000));

            const year = witTime.getUTCFullYear();
            const month = String(witTime.getUTCMonth() + 1).padStart(2, '0');
            const day = String(witTime.getUTCDate()).padStart(2, '0');
            const hours = String(witTime.getUTCHours()).padStart(2, '0');
            const minutes = String(witTime.getUTCMinutes()).padStart(2, '0');

            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }

        // Format datetime for display in WIT
        function formatDateTimeWIT(dateTimeString) {
            if (!dateTimeString) return '-';
            const date = new Date(dateTimeString);
            const witDate = new Date(date.getTime() + (9 * 60 * 60 * 1000));
            return witDate.toLocaleString('id-ID', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
                timeZone: 'Asia/Jayapura'
            });
        }

        // ===================================================
        // DELETE MODAL FUNCTIONS
        // ===================================================
        function openDeleteModal(id, nomorTicket, namaPelapor) {
            document.getElementById('deleteLaporanId').value = id;
            document.getElementById('deleteLaporanInfo').textContent = nomorTicket + ' - ' + namaPelapor;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
            document.getElementById('deleteForm').reset();
        }

        // Handle delete submit
        document.getElementById('deleteForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const laporanId = document.getElementById('deleteLaporanId').value;

            fetch(`/laporan/${laporanId}`, {
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
                });
        });

        // ===================================================
        // STATUS MODAL FUNCTIONS WITH WIT TIMEZONE
        // ===================================================

        // Open status modal
        function openStatusModal(id, currentStatus) {
            const statusModal = document.getElementById('statusModal');
            const statusLaporanId = document.getElementById('statusLaporanId');
            const statusSelect = document.getElementById('status');
            const tanggalSelesaiInput = document.getElementById('tanggal_selesai_modal');

            // Set values
            statusLaporanId.value = id;
            statusSelect.value = currentStatus;
            currentStatusValue = currentStatus;

            // Reset all description containers
            const pendingContainer = document.getElementById('pendingDeskripsiContainer');
            const escalateContainer = document.getElementById('escalateDeskripsiContainer');
            const doneContainer = document.getElementById('doneDeskripsiContainer');
            const tanggalContainer = document.getElementById('tanggalSelesaiContainer');

            pendingContainer.classList.add('hidden');
            escalateContainer.classList.add('hidden');
            doneContainer.classList.add('hidden');
            tanggalContainer.classList.add('hidden');

            // Clear values
            document.getElementById('pending_deskripsi').value = '';
            document.getElementById('escalate_deskripsi').value = '';
            document.getElementById('solusi').value = '';

            // Set tanggal selesai with current WIT datetime
            tanggalSelesaiInput.value = getCurrentDateTimeWIT();

            // Show container for current status
            toggleStatusFields(currentStatus);

            // Show modal
            statusModal.classList.remove('hidden');
            statusModal.classList.add('flex');
        }

        // Close status modal
        function closeStatusModal() {
            const statusModal = document.getElementById('statusModal');
            statusModal.classList.add('hidden');
            statusModal.classList.remove('flex');
            document.getElementById('statusForm').reset();
        }

        // Toggle fields based on selected status
        function toggleStatusFields(status) {
            const pendingContainer = document.getElementById('pendingDeskripsiContainer');
            const escalateContainer = document.getElementById('escalateDeskripsiContainer');
            const doneContainer = document.getElementById('doneDeskripsiContainer');
            const tanggalContainer = document.getElementById('tanggalSelesaiContainer');
            const pendingDesc = document.getElementById('pending_deskripsi');
            const escalateDesc = document.getElementById('escalate_deskripsi');
            const solusi = document.getElementById('solusi');

            // Hide all containers
            pendingContainer.classList.add('hidden');
            escalateContainer.classList.add('hidden');
            doneContainer.classList.add('hidden');
            tanggalContainer.classList.add('hidden');

            // Remove required attributes from all
            pendingDesc.removeAttribute('required');
            escalateDesc.removeAttribute('required');
            solusi.removeAttribute('required');

            // Show based on status
            if (status === 'pending') {
                pendingContainer.classList.remove('hidden');
                pendingDesc.setAttribute('required', 'required');
            } else if (status === 'escalate') {
                escalateContainer.classList.remove('hidden');
                escalateDesc.setAttribute('required', 'required');
            } else if (status === 'done') {
                doneContainer.classList.remove('hidden');
                tanggalContainer.classList.remove('hidden');
                solusi.setAttribute('required', 'required');

                // Update datetime when done is selected (WIT timezone)
                const tanggalSelesaiInput = document.getElementById('tanggal_selesai_modal');
                tanggalSelesaiInput.value = getCurrentDateTimeWIT();
            }
        }

        // Add event listener for status change in modal
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            if (statusSelect) {
                statusSelect.addEventListener('change', function() {
                    toggleStatusFields(this.value);
                });
            }
        });

        // Handle status update submit
        document.getElementById('statusForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const laporanId = document.getElementById('statusLaporanId').value;
            const status = document.getElementById('status').value;

            // Prepare data
            const formData = {
                status: status,
                _method: 'PUT',
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };

            // Add description based on status
            if (status === 'pending') {
                const pendingDesc = document.getElementById('pending_deskripsi').value.trim();
                if (!pendingDesc) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Alasan pending harus diisi!'
                    });
                    return;
                }
                formData.pending_deskripsi = pendingDesc;
            } else if (status === 'escalate') {
                const escalateDesc = document.getElementById('escalate_deskripsi').value.trim();
                if (!escalateDesc) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Alasan escalate harus diisi!'
                    });
                    return;
                }
                formData.escalate_deskripsi = escalateDesc;
            } else if (status === 'done') {
                const solusi = document.getElementById('solusi').value.trim();
                if (!solusi) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Solusi harus diisi!'
                    });
                    return;
                }
                formData.solusi = solusi;

                // Get tanggal selesai from readonly input (already in WIT timezone)
                const tanggalSelesai = document.getElementById('tanggal_selesai_modal').value;
                if (tanggalSelesai) {
                    // Convert from datetime-local format to database format (YYYY-MM-DD HH:mm:ss)
                    // The value is already in WIT timezone
                    formData.tanggal_selesai = tanggalSelesai.replace('T', ' ') + ':00';
                } else {
                    formData.tanggal_selesai = getCurrentDateTimeWIT().replace('T', ' ') + ':00';
                }
            }

            // Show loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Mengupdate status laporan',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
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
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Status laporan berhasil diperbarui',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            closeStatusModal();
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message || 'Gagal mengupdate status'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat mengupdate status'
                    });
                });
        });

        // ===================================================
        // PDF MODAL FUNCTIONS
        // ===================================================
        function openPdfModal() {
            // Update hidden inputs dengan nilai filter saat ini
            document.getElementById('pdfSearch').value = searchInput ? searchInput.value : '';
            document.getElementById('pdfKantor').value = filterKantor ? filterKantor.value : '';
            document.getElementById('pdfAplikasi').value = filterAplikasi ? filterAplikasi.value : '';
            document.getElementById('pdfStatus').value = filterStatus ? filterStatus.value : '';
            document.getElementById('pdfTanggalAwal').value = tanggalAwal ? tanggalAwal.value : '';
            document.getElementById('pdfTanggalAkhir').value = tanggalAkhir ? tanggalAkhir.value : '';

            document.getElementById('pdfModal').classList.remove('hidden');
            document.getElementById('pdfModal').classList.add('flex');
        }

        function closePdfModal() {
            document.getElementById('pdfModal').classList.add('hidden');
            document.getElementById('pdfModal').classList.remove('flex');
        }

        // ===================================================
        // WHATSAPP FUNCTION
        // ===================================================
        function sendWhatsApp(laporanId) {
            Swal.fire({
                title: 'Memproses...',
                text: 'Menyiapkan data laporan',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/laporan/${laporanId}/whatsapp`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.close();
                        const message = encodeURIComponent(data.message);
                        const whatsappUrl = `https://wa.me/${data.phone_number}?text=${message}`;
                        window.open(whatsappUrl, '_blank');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Pesan WhatsApp telah disiapkan',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message || 'Gagal mempersiapkan pesan WhatsApp'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat memproses permintaan'
                    });
                });
        }

        // ===================================================
        // FILTER FUNCTIONALITY
        // ===================================================
        function fetchLaporans() {
            const search = searchInput ? searchInput.value : '';
            const kantor = filterKantor ? filterKantor.value : '';
            const aplikasi = filterAplikasi ? filterAplikasi.value : '';
            const status = filterStatus ? filterStatus.value : '';
            const tglAwal = tanggalAwal ? tanggalAwal.value : '';
            const tglAkhir = tanggalAkhir ? tanggalAkhir.value : '';

            if (tglAwal && tglAkhir && tglAwal > tglAkhir) {
                alert('Tanggal akhir harus lebih besar atau sama dengan tanggal awal');
                return;
            }

            tableContainer.innerHTML =
                '<div class="text-center py-8"><div class="loading mx-auto"></div><p class="text-gray-400 mt-2">Loading...</p></div>';

            const url = new URL(window.location.href);
            url.searchParams.set('search', search);
            url.searchParams.set('kantor', kantor);
            url.searchParams.set('aplikasi', aplikasi);
            url.searchParams.set('status', status);
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
                    console.error('Error:', error);
                    tableContainer.innerHTML = '<div class="text-center py-8 text-red-400">Error loading data</div>';
                });
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

        function resetFilters() {
            if (searchInput) searchInput.value = '';
            if (filterKantor) filterKantor.value = '';
            if (filterAplikasi) filterAplikasi.value = '';
            if (filterStatus) filterStatus.value = '';
            if (tanggalAwal) tanggalAwal.value = '';
            if (tanggalAkhir) tanggalAkhir.value = '';
            fetchLaporans();
        }

        // Event listeners
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(fetchLaporans, 500);
            });
        }

        [filterKantor, filterAplikasi, filterStatus, tanggalAwal, tanggalAkhir].forEach(filter => {
            if (filter) {
                filter.addEventListener('change', fetchLaporans);
            }
        });

        // Set max date untuk tanggal akhir
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

        // Initial attach of pagination listeners
        attachPaginationListeners();

        // Set initial values from URL
        if (tanggalAwal && tanggalAkhir) {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('tanggal_awal')) {
                tanggalAwal.value = urlParams.get('tanggal_awal');
            }
            if (urlParams.get('tanggal_akhir')) {
                tanggalAkhir.value = urlParams.get('tanggal_akhir');
            }
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            const deleteModal = document.getElementById('deleteModal');
            const statusModal = document.getElementById('statusModal');
            const pdfModal = document.getElementById('pdfModal');

            if (e.target === deleteModal) {
                closeDeleteModal();
            }
            if (e.target === statusModal) {
                closeStatusModal();
            }
            if (e.target === pdfModal) {
                closePdfModal();
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
    </script>
@endpush
