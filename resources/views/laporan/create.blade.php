@extends('layouts.app')

@section('title', 'Buat Laporan Baru - E-Ticketing System')

@section('content')
    <div class="min-h-screen bg-[#001D39]">
        <!-- Konten halaman dengan scrollbar custom - full page tanpa sidebar -->
        <div class="overflow-y-auto" style="height: 100vh;">
            <!-- Header dengan Logo dan Credit - Fully Responsive -->
            <div class="max-w-5xl mx-auto pt-4 sm:pt-6 md:pt-8 px-3 sm:px-4 md:px-6 lg:px-8 mb-4 sm:mb-6">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-0">
                    <!-- Logo di Ujung Kiri (Mobile: tengah, Desktop: kiri) -->
                    <div class="flex items-center group">
                        <img src="{{ asset('assets/logo1.PNG') }}" alt="Logo E-Ticketing"
                            class="h-8 sm:h-10 md:h-12 lg:h-13 w-auto brightness-0 invert drop-shadow-2xl transform group-hover:scale-110 transition-transform duration-500"
                            onerror="this.style.display='none'">
                    </div>

                    <!-- Credit di Ujung Kanan (Mobile: tengah, Desktop: kanan) -->
                    <div class="flex items-center justify-center space-x-1.5 sm:space-x-2">
                        <span class="text-white font-medium text-xs sm:text-sm md:text-base">DEVELOP BY</span>
                        <span
                            class="bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent font-bold text-sm sm:text-base md:text-lg">
                            IT DIGITAL
                        </span>
                    </div>
                </div>
            </div>

            <!-- Header lebih ringkas -->
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                <div class="flex items-center justify-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white text-center">
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-green-400 to-emerald-400">
                                Buat Laporan Baru
                            </span>
                        </h1>
                        <p class="text-gray-300 text-sm mt-1">Isi form berikut untuk membuat laporan tiket baru</p>
                    </div>
                </div>
            </div>

            <!-- Form Card dengan layout grid yang lebih efisien -->
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
                <!-- Info Card ringkas -->
                <div class="mb-4 bg-blue-500/10 border border-blue-500/20 rounded-xl p-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-xs text-blue-200">Setiap laporan akan mendapatkan nomor tiket unik untuk tracking
                            status
                            laporan Anda.</p>
                    </div>
                </div>

                <!-- Form dengan glassmorphism -->
                <div class="backdrop-blur-xl bg-white/5 border border-white/10 rounded-xl shadow-2xl p-5">
                    <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data"
                        id="laporanForm">
                        @csrf

                        <!-- Grid 2 kolom untuk input yang lebih rapat -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nama Pelapor -->
                            <div>
                                <label for="nama_pelapor" class="block text-xs font-medium text-gray-300 mb-1">
                                    Nama Pelapor <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="text" id="nama_pelapor" name="nama_pelapor"
                                        value="{{ old('nama_pelapor') }}"
                                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg pl-8 pr-3 py-2 text-sm focus:border-green-400 focus:outline-none transition-colors @error('nama_pelapor') border-red-400 @enderror"
                                        placeholder="Nama lengkap" required>
                                </div>
                                @error('nama_pelapor')
                                    <p class="text-red-400 text-xs mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- No Handphone -->
                            <div>
                                <label for="no_handphone" class="block text-xs font-medium text-gray-300 mb-1">
                                    No. Handphone <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="tel" id="no_handphone" name="no_handphone"
                                        value="{{ old('no_handphone') }}"
                                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg pl-8 pr-3 py-2 text-sm focus:border-green-400 focus:outline-none transition-colors @error('no_handphone') border-red-400 @enderror"
                                        placeholder="08xxxxxxxxxx" required pattern="[0-9]{10,13}">
                                </div>
                                @error('no_handphone')
                                    <p class="text-red-400 text-xs mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kantor -->
                            <div>
                                <label for="kantor_id" class="block text-xs font-medium text-gray-300 mb-1">
                                    Kantor <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <select id="kantor_id" name="kantor_id"
                                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-3 py-2 text-sm appearance-none focus:border-green-400 focus:outline-none transition-colors @error('kantor_id') border-red-400 @enderror"
                                        required>
                                        <option value="" class="bg-[#001D39] text-gray-400">Pilih Kantor</option>
                                        @foreach ($kantors as $kantor)
                                            <option value="{{ $kantor->id }}"
                                                {{ old('kantor_id') == $kantor->id ? 'selected' : '' }}
                                                class="bg-[#001D39] text-white">
                                                {{ $kantor->kode_cabang }} - {{ $kantor->nama_cabang }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('kantor_id')
                                    <p class="text-red-400 text-xs mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status Awal -->
                            <div>
                                <label for="status" class="block text-xs font-medium text-gray-300 mb-1">
                                    Status Awal
                                </label>
                                <div class="relative">
                                    <input type="text" id="status" value="Baru" readonly disabled
                                        class="w-full bg-white/5 border border-white/10 text-green-400 rounded-lg px-3 py-2 text-sm opacity-75 cursor-not-allowed">
                                </div>
                            </div>

                            <!-- Jenis Aplikasi -->
                            <div>
                                <label for="jenis_aplikasi_id" class="block text-xs font-medium text-gray-300 mb-1">
                                    Jenis Aplikasi <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <select id="jenis_aplikasi_id" name="jenis_aplikasi_id"
                                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-3 py-2 text-sm appearance-none focus:border-green-400 focus:outline-none transition-colors @error('jenis_aplikasi_id') border-red-400 @enderror"
                                        required>
                                        <option value="" class="bg-[#001D39] text-gray-400">Pilih Jenis Aplikasi
                                        </option>
                                        @foreach ($jenisAplikasis as $jenis)
                                            <option value="{{ $jenis->id }}"
                                                {{ old('jenis_aplikasi_id') == $jenis->id ? 'selected' : '' }}
                                                class="bg-[#001D39] text-white">
                                                {{ $jenis->jenis_aplikasi }} - {{ $jenis->deskripsi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('jenis_aplikasi_id')
                                    <p class="text-red-400 text-xs mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kode/Nama Produk -->
                            <div>
                                <label for="kode_produk_id" class="block text-xs font-medium text-gray-300 mb-1">
                                    Kode/Nama Produk <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <select id="kode_produk_id" name="kode_produk_id"
                                        class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-3 py-2 text-sm appearance-none focus:border-green-400 focus:outline-none transition-colors @error('kode_produk_id') border-red-400 @enderror"
                                        required disabled>
                                        <option value="" class="bg-[#001D39] text-gray-400">Pilih Jenis Aplikasi
                                            Terlebih Dahulu</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div id="product-loading" class="hidden text-green-400 text-xs mt-1">
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="animate-spin h-3 w-3" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Memuat produk...
                                    </span>
                                </div>
                                @error('kode_produk_id')
                                    <p class="text-red-400 text-xs mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Kronologi - Full width -->
                        <div class="mt-4">
                            <label for="kronologi" class="block text-xs font-medium text-gray-300 mb-1">
                                Kronologi <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute top-2 left-2 pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h7"></path>
                                    </svg>
                                </div>
                                <textarea id="kronologi" name="kronologi" rows="3"
                                    class="w-full bg-white/5 border border-white/10 text-white rounded-lg pl-8 pr-3 py-2 text-sm focus:border-green-400 focus:outline-none transition-colors @error('kronologi') border-red-400 @enderror"
                                    placeholder="Jelaskan kronologi kejadian secara detail..." required>{{ old('kronologi') }}</textarea>
                            </div>
                            @error('kronologi')
                                <p class="text-red-400 text-xs mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Multiple Lampiran dengan preview -->
                        <div class="mt-4">
                            <label for="lampiran" class="block text-xs font-medium text-gray-300 mb-1">
                                Lampiran (Multiple files allowed)
                            </label>
                            <div id="dropzone-area"
                                class="relative border-2 border-dashed border-white/10 rounded-lg p-3 hover:border-green-500/50 transition-all duration-300 cursor-pointer group">
                                <input type="file" id="lampiran" name="lampiran[]" multiple
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">

                                <!-- Multiple Preview Container -->
                                <div id="preview-container" class="hidden mb-2">
                                    <div id="files-preview" class="grid grid-cols-4 md:grid-cols-6 gap-2">
                                        <!-- Preview items akan ditambahkan di sini -->
                                    </div>
                                </div>

                                <!-- Upload Placeholder lebih ringkas -->
                                <div id="upload-placeholder" class="flex items-center justify-center gap-3">
                                    <svg class="h-8 w-8 text-gray-400 group-hover:text-green-400 transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-400">
                                            <span class="text-green-400 font-semibold">Klik untuk upload</span> atau drag
                                            and
                                            drop
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5">JPG, PNG, PDF, DOC (Max. 2MB each)</p>
                                    </div>
                                    <span id="file-count" class="text-xs text-green-400 ml-auto"></span>
                                </div>
                            </div>
                            @error('lampiran.*')
                                <p class="text-red-400 text-xs mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons lebih ringkas -->
                        <div class="flex items-center justify-end gap-3 mt-5 pt-3 border-t border-white/10">
                            <a href="/"
                                class="px-4 py-2 border border-white/10 text-gray-300 rounded-lg hover:bg-white/5 transition-colors text-sm">
                                Batal
                            </a>
                            <button type="submit" id="submitBtn"
                                class="group relative px-5 py-2 overflow-hidden rounded-lg bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 text-white text-sm font-medium transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-green-500/30 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="relative z-10 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Kirim Laporan
                                </span>
                                <div
                                    class="absolute inset-0 bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.2)_50%,transparent_75%)] translate-x-[-200%] group-hover:translate-x-[200%] transition-transform duration-1000">
                                </div>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer Info ringkas -->
                <div class="mt-4 text-center text-xs text-gray-400">
                    <p>Dengan mengirim laporan, Anda menyetujui
                        <a href="#" class="text-green-400 hover:text-green-300">Ketentuan Layanan</a> dan
                        <a href="#" class="text-green-400 hover:text-green-300">Kebijakan Privasi</a> kami.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 z-[9999] overflow-y-auto hidden" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black/60 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>

            <!-- Modal panel -->
            <div
                class="relative bg-gradient-to-br from-[#001D39] to-[#002B4F] rounded-2xl shadow-2xl w-full max-w-md mx-auto border border-green-500/20 pointer-events-auto max-h-[90vh] overflow-y-auto">
                <!-- Header -->
                <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-green-500 to-emerald-500 rounded-t-2xl">
                </div>

                <!-- Content -->
                <div class="px-5 pt-6 pb-5">
                    <!-- Icon -->
                    <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-green-500 mb-3">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>

                    <!-- Title -->
                    <h3 class="text-center text-xl font-bold text-white mb-3">
                        Laporan Terkirim!
                    </h3>

                    <!-- Ticket Info -->
                    <div class="bg-green-500/10 rounded-lg p-3 border border-green-500/20 mb-3">
                        <div class="text-center mb-1">
                            <span class="text-gray-400 text-xs">Nomor Tiket</span>
                            <span
                                class="ml-2 text-green-400 text-[10px] px-1.5 py-0.5 bg-green-500/20 rounded-full">BARU</span>
                        </div>
                        <p id="ticketNumber"
                            class="text-center text-xl font-mono font-bold text-green-400 break-all select-all">
                        </p>
                        <p class="text-center text-[10px] text-gray-400 mt-1">Simpan nomor tiket untuk tracking laporan</p>
                    </div>

                    <!-- Detail -->
                    <div class="space-y-1.5 bg-white/5 rounded-lg p-3 mb-3">
                        <div class="flex items-center gap-2 text-xs">
                            <svg class="w-3.5 h-3.5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span id="pelaporName" class="text-gray-300 text-xs"></span>
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <svg class="w-3.5 h-3.5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span id="tanggalLaporan" class="text-gray-300 text-xs"></span>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="bg-blue-500/10 rounded-lg p-2 border border-blue-500/20">
                        <p class="text-[10px] text-blue-200 text-center leading-relaxed">
                            Kami akan segera memproses laporan Anda. Status laporan dapat dicek menggunakan nomor tiket di
                            atas.
                        </p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-5 pb-5 flex flex-col sm:flex-row gap-2">
                    <button onclick="copyTicketNumber()" id="copyButton"
                        class="flex-1 inline-flex justify-center items-center gap-1.5 px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 text-white text-sm font-medium rounded-lg transition-colors cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                            </path>
                        </svg>
                        Salin Nomor Tiket
                    </button>
                    <a href="/"
                        class="flex-1 inline-flex justify-center items-center gap-1.5 px-3 py-2 bg-white/5 hover:bg-white/10 text-white text-sm font-medium rounded-lg border border-white/10 transition-colors cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7-7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>

                <!-- Close button -->
                <button onclick="closeModal()"
                    class="absolute top-3 right-3 text-gray-400 hover:text-white transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="fixed inset-0 z-[9999] overflow-y-auto hidden" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black/60 transition-opacity" aria-hidden="true" onclick="closeErrorModal()">
            </div>

            <!-- Modal panel -->
            <div
                class="relative bg-gradient-to-br from-[#001D39] to-[#002B4F] rounded-2xl shadow-2xl w-full max-w-md mx-auto border border-red-500/20 pointer-events-auto max-h-[90vh] overflow-y-auto">
                <!-- Header -->
                <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-red-500 to-pink-500 rounded-t-2xl"></div>

                <!-- Content -->
                <div class="px-5 pt-6 pb-5">
                    <!-- Icon -->
                    <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-500 mb-3">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>

                    <!-- Title -->
                    <h3 class="text-center text-xl font-bold text-white mb-3">
                        Gagal Mengirim Laporan!
                    </h3>

                    <!-- Message -->
                    <div id="errorMessage" class="bg-red-500/10 rounded-lg p-3 border border-red-500/20">
                        <p class="text-red-200 text-center text-sm"></p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-5 pb-5 flex justify-center">
                    <button onclick="closeErrorModal()"
                        class="inline-flex justify-center items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-500 hover:to-pink-500 text-white text-sm font-medium rounded-lg transition-colors cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Tutup
                    </button>
                </div>

                <!-- Close button -->
                <button onclick="closeErrorModal()"
                    class="absolute top-3 right-3 text-gray-400 hover:text-white transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom scrollbar untuk konten - SAMA SEPERTI HALAMAN DATA KANTOR */
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

        /* Hide modal by default */
        .hidden {
            display: none !important;
        }

        /* File input styling */
        input[type="file"] {
            cursor: pointer;
        }

        /* Preview styling lebih kecil */
        .preview-item {
            position: relative;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid rgba(16, 185, 129, 0.3);
            transition: all 0.2s ease;
        }

        .preview-item:hover {
            border-color: #10b981;
            transform: scale(1.03);
        }

        .preview-item img {
            width: 100%;
            height: 50px;
            object-fit: cover;
        }

        .preview-item .file-info {
            padding: 3px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            font-size: 0.6rem;
            text-align: center;
        }

        .preview-item .remove-file {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.2s ease;
            z-index: 10;
            border: none;
            padding: 0;
        }

        .preview-item:hover .remove-file {
            opacity: 1;
        }

        .preview-item .remove-file:hover {
            background: #dc2626;
        }

        #dropzone-area.dragover {
            border-color: #10b981;
            background: rgba(16, 185, 129, 0.1);
        }

        /* Loading spinner lebih kecil */
        .spinner {
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 2px solid #10b981;
            width: 14px;
            height: 14px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Select all text on click */
        .select-all {
            user-select: all;
        }

        /* Notification styling */
        .copy-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(to right, #059669, #10b981);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 10001;
            font-size: 12px;
            animation: fadeInOut 2s ease-in-out;
            max-width: 250px;
            white-space: nowrap;
        }

        @keyframes fadeInOut {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }

            10% {
                opacity: 1;
                transform: translateY(0);
            }

            90% {
                opacity: 1;
                transform: translateY(0);
            }

            100% {
                opacity: 0;
                transform: translateY(-10px);
            }
        }

        /* Form input styling */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        textarea:-webkit-autofill,
        textarea:-webkit-autofill:hover,
        textarea:-webkit-autofill:focus,
        select:-webkit-autofill,
        select:-webkit-autofill:hover,
        select:-webkit-autofill:focus {
            -webkit-text-fill-color: white;
            -webkit-box-shadow: 0 0 0px 1000px rgba(0, 29, 57, 0.5) inset;
            transition: background-color 5000s ease-in-out 0s;
            border-color: rgba(255, 255, 255, 0.1);
        }

        select option {
            background-color: #001D39;
            color: white;
        }

        select option:hover {
            background-color: #002B4F;
        }

        /* ============================================ */
        /* RESPONSIVE UNTUK MOBILE SAJA (max-width: 768px) */
        /* SAMA SEPERTI HALAMAN DATA KANTOR */
        /* ============================================ */
        @media (max-width: 768px) {

            /* Konten utama - kurangi padding */
            .max-w-5xl.mx-auto.pt-8 {
                padding-top: 1rem !important;
            }

            .p-5 {
                padding: 1rem !important;
            }

            /* Header - perkecil margin dan font */
            .text-3xl {
                font-size: 1.25rem !important;
                line-height: 1.75rem !important;
            }

            .text-gray-300.text-sm {
                font-size: 0.75rem !important;
            }

            /* Form grid jadi column */
            .grid {
                gap: 0.75rem !important;
            }

            /* Tombol */
            .flex.items-center.justify-end {
                flex-direction: column !important;
                gap: 0.5rem !important;
            }

            .flex.items-center.justify-end a,
            .flex.items-center.justify-end button {
                width: 100% !important;
                justify-content: center !important;
            }

            /* Modal - perkecil padding */
            #successModal .p-6,
            #errorModal .p-6 {
                padding: 1rem !important;
            }

            /* Modal header */
            #successModal .text-xl,
            #errorModal .text-xl {
                font-size: 1rem !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        let currentTicketNumber = '';
        let selectedFiles = [];

        // Dynamic product loading
        document.addEventListener('DOMContentLoaded', function() {
            const jenisAplikasiSelect = document.getElementById('jenis_aplikasi_id');
            const produkSelect = document.getElementById('kode_produk_id');
            const productLoading = document.getElementById('product-loading');

            const oldJenisAplikasi = '{{ old('jenis_aplikasi_id') }}';
            const oldProdukId = '{{ old('kode_produk_id') }}';

            if (oldJenisAplikasi) {
                jenisAplikasiSelect.value = oldJenisAplikasi;
                loadProducts(oldJenisAplikasi, oldProdukId);
            }

            jenisAplikasiSelect.addEventListener('change', function() {
                const aplikasiId = this.value;
                if (aplikasiId) {
                    loadProducts(aplikasiId);
                } else {
                    produkSelect.innerHTML =
                        '<option value="" class="bg-[#001D39] text-gray-400">Pilih Jenis Aplikasi Terlebih Dahulu</option>';
                    produkSelect.disabled = true;
                }
            });

            function loadProducts(aplikasiId, selectedProductId = null) {
                productLoading.classList.remove('hidden');
                produkSelect.disabled = true;
                produkSelect.innerHTML =
                    '<option value="" class="bg-[#001D39] text-gray-400">Memuat produk...</option>';

                const url = `/laporan/get-products/${aplikasiId}`;

                fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                                'content') || ''
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success' && data.products && data.products.length > 0) {
                            let options =
                                '<option value="" class="bg-[#001D39] text-gray-400">Pilih Produk</option>';
                            data.products.forEach(product => {
                                const selected = (selectedProductId && selectedProductId == product
                                    .id) ? 'selected' : '';
                                const productText = product.kode_produk ?
                                    `${product.kode_produk} - ${product.nama_produk}` : product
                                    .nama_produk;
                                options +=
                                    `<option value="${product.id}" class="bg-[#001D39] text-white" ${selected}>${productText}</option>`;
                            });
                            produkSelect.innerHTML = options;
                            produkSelect.disabled = false;
                        } else if (data.status === 'success' && (!data.products || data.products.length ===
                                0)) {
                            produkSelect.innerHTML =
                                '<option value="" class="bg-[#001D39] text-gray-400">Tidak ada produk untuk aplikasi ini</option>';
                            produkSelect.disabled = true;
                        } else {
                            produkSelect.innerHTML = '<option value="" class="bg-[#001D39] text-gray-400">' + (
                                data.message || 'Gagal memuat produk') + '</option>';
                            produkSelect.disabled = true;
                        }
                        productLoading.classList.add('hidden');
                    })
                    .catch(error => {
                        console.error('Error loading products:', error);
                        produkSelect.innerHTML = '<option value="" class="bg-[#001D39] text-gray-400">Error: ' +
                            error.message + '</option>';
                        produkSelect.disabled = true;
                        productLoading.classList.add('hidden');
                    });
            }
        });

        // Validasi form sebelum submit dengan AJAX
        document.getElementById('laporanForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const requiredFields = ['nama_pelapor', 'no_handphone', 'kantor_id', 'jenis_aplikasi_id',
                'kode_produk_id', 'kronologi'
            ];
            let isValid = true;

            // Reset error styling
            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                if (input) {
                    input.classList.remove('border-red-400');
                    const errorDiv = input.parentNode?.parentNode?.querySelector(
                        '.text-red-400:not(.server-error)');
                    if (errorDiv) errorDiv.remove();
                }
            });

            // Validasi field required
            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                if (input && !input.value) {
                    input.classList.add('border-red-400');
                    isValid = false;

                    let errorDiv = input.parentNode?.parentNode?.querySelector(
                        '.text-red-400:not(.server-error)');
                    if (!errorDiv) {
                        errorDiv = document.createElement('p');
                        errorDiv.className = 'text-red-400 text-xs mt-1';
                        errorDiv.textContent = 'Field ini wajib diisi';
                        input.parentNode?.parentNode?.appendChild(errorDiv);
                    }
                }
            });

            // Validasi no handphone
            const noHp = document.getElementById('no_handphone');
            if (noHp) {
                const noHpValue = noHp.value.replace(/\D/g, '');
                if (noHpValue.length < 10 || noHpValue.length > 13) {
                    noHp.classList.add('border-red-400');
                    isValid = false;

                    let errorDiv = noHp.parentNode?.parentNode?.querySelector(
                        '.text-red-400:not(.server-error)');
                    if (!errorDiv) {
                        errorDiv = document.createElement('p');
                        errorDiv.className = 'text-red-400 text-xs mt-1';
                        noHp.parentNode?.parentNode?.appendChild(errorDiv);
                    }
                    errorDiv.textContent = 'Nomor handphone harus 10-13 digit angka';
                }
            }

            if (!isValid) {
                alert('Mohon lengkapi semua field yang wajib diisi dengan benar');
                return;
            }

            // Tampilkan loading
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML =
                '<span class="relative z-10 flex items-center justify-center gap-2"><div class="spinner"></div>Mengirim...</span>';

            try {
                const formData = new FormData(this);
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();

                if (result.status === 'success') {
                    currentTicketNumber = result.data.ticket_number;
                    document.getElementById('ticketNumber').textContent = result.data.ticket_number;
                    document.getElementById('pelaporName').textContent = result.data.nama_pelapor;
                    document.getElementById('tanggalLaporan').textContent = result.data.tanggal;
                    document.getElementById('successModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                    this.reset();
                    resetPreviews();
                    document.getElementById('kode_produk_id').disabled = true;
                    document.getElementById('kode_produk_id').innerHTML =
                        '<option value="">Pilih Jenis Aplikasi Terlebih Dahulu</option>';
                } else {
                    showErrorModal(result.message || 'Terjadi kesalahan saat mengirim laporan');
                }
            } catch (error) {
                console.error('Error:', error);
                showErrorModal('Terjadi kesalahan saat mengirim laporan');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });

        // Real-time validation
        document.getElementById('no_handphone')?.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Multiple file upload
        const dropzone = document.getElementById('dropzone-area');
        const fileInput = document.getElementById('lampiran');
        const previewContainer = document.getElementById('preview-container');
        const uploadPlaceholder = document.getElementById('upload-placeholder');
        const filesPreview = document.getElementById('files-preview');
        const fileCount = document.getElementById('file-count');

        if (dropzone) {
            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('dragover');
            });

            dropzone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('dragover');
            });

            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('dragover');
                handleFiles(Array.from(e.dataTransfer.files));
            });
        }

        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                handleFiles(Array.from(e.target.files));
            });
        }

        function handleFiles(files) {
            const validFiles = [];

            for (let file of files) {
                const isDuplicate = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                if (isDuplicate) {
                    alert(`File ${file.name} sudah ditambahkan`);
                    continue;
                }

                if (file.size > 2 * 1024 * 1024) {
                    alert(`File ${file.name} terlalu besar! Maksimal 2MB.`);
                    continue;
                }

                const allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
                const fileExtension = file.name.split('.').pop().toLowerCase();

                if (!allowedExtensions.includes(fileExtension)) {
                    alert(`Tipe file ${file.name} tidak diizinkan! Format yang diizinkan: JPG, PNG, PDF, DOC`);
                    continue;
                }

                validFiles.push(file);
            }

            if (validFiles.length > 0) {
                selectedFiles = [...selectedFiles, ...validFiles];
                updateFileInput();
                displayPreviews();
            }
        }

        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            if (fileInput) fileInput.files = dataTransfer.files;
            if (fileCount) fileCount.textContent = `${selectedFiles.length} file(s) dipilih`;
        }

        function displayPreviews() {
            if (selectedFiles.length > 0 && previewContainer && uploadPlaceholder && filesPreview) {
                previewContainer.classList.remove('hidden');
                uploadPlaceholder.classList.add('hidden');
                filesPreview.innerHTML = '';

                selectedFiles.forEach((file, index) => {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'preview-item';
                    previewItem.setAttribute('data-index', index);

                    const fileSize = (file.size / 1024).toFixed(2);
                    const fileName = file.name.length > 15 ? file.name.substring(0, 12) + '...' : file.name;

                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewItem.innerHTML = `
                                <img src="${e.target.result}" alt="Preview">
                                <div class="file-info">
                                    <p class="truncate" title="${file.name}">${fileName}</p>
                                    <p class="text-[8px] opacity-75">${fileSize} KB</p>
                                </div>
                                <button type="button" class="remove-file" onclick="removeFile(${index})">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            `;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        let icon = '';
                        let bgColor = '';

                        if (file.type === 'application/pdf') {
                            icon =
                                '<svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>';
                            bgColor = 'bg-red-500/10';
                        } else if (file.type.includes('word') || file.name.match(/\.docx?$/)) {
                            icon =
                                '<svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
                            bgColor = 'bg-blue-500/10';
                        } else {
                            icon =
                                '<svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>';
                            bgColor = 'bg-gray-500/10';
                        }

                        previewItem.innerHTML = `
                            <div class="p-2 text-center ${bgColor}">
                                ${icon}
                                <div class="file-info mt-1">
                                    <p class="truncate text-[9px]" title="${file.name}">${fileName}</p>
                                    <p class="text-[8px] opacity-75">${fileSize} KB</p>
                                </div>
                            </div>
                            <button type="button" class="remove-file" onclick="removeFile(${index})">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        `;
                    }

                    filesPreview.appendChild(previewItem);
                });
            } else {
                resetPreviews();
            }
        }

        window.removeFile = function(index) {
            selectedFiles.splice(index, 1);
            updateFileInput();
            selectedFiles.length > 0 ? displayPreviews() : resetPreviews();
        };

        function resetPreviews() {
            if (previewContainer && uploadPlaceholder && filesPreview && fileCount) {
                previewContainer.classList.add('hidden');
                uploadPlaceholder.classList.remove('hidden');
                filesPreview.innerHTML = '';
                fileCount.textContent = '';
            }
            selectedFiles = [];
            if (fileInput) fileInput.value = '';
        }

        // Reset validation styling
        document.querySelectorAll(
            '#nama_pelapor, #no_handphone, #kantor_id, #jenis_aplikasi_id, #kode_produk_id, #kronologi').forEach(
            field => {
                if (field) {
                    field.addEventListener('input', function() {
                        this.classList.remove('border-red-400');
                        const errorDiv = this.parentNode?.parentNode?.querySelector(
                            '.text-red-400:not(.server-error)');
                        if (errorDiv) errorDiv.remove();
                    });
                }
            });

        // Modal functions
        function showErrorModal(message) {
            const errorMsg = document.querySelector('#errorMessage p');
            if (errorMsg) errorMsg.textContent = message;
            const modal = document.getElementById('errorModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal() {
            const modal = document.getElementById('successModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        function closeErrorModal() {
            const modal = document.getElementById('errorModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        function copyTicketNumber() {
            const ticketNumber = document.getElementById('ticketNumber').textContent.trim();
            if (!ticketNumber) return;

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(ticketNumber).then(() => showCopyNotification());
            } else {
                const textarea = document.createElement('textarea');
                textarea.value = ticketNumber;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                showCopyNotification();
            }
        }

        function showCopyNotification() {
            const existing = document.querySelector('.copy-notification');
            if (existing) existing.remove();
            const notification = document.createElement('div');
            notification.className = 'copy-notification';
            notification.innerHTML = '✓ Nomor tiket berhasil disalin!';
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 2000);
        }

        window.onclick = function(event) {
            const successModal = document.getElementById('successModal');
            const errorModal = document.getElementById('errorModal');
            if (event.target === successModal) closeModal();
            if (event.target === errorModal) closeErrorModal();
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
                closeErrorModal();
            }
        });
    </script>
@endpush
