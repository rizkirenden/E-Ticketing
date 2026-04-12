@extends('layouts.app')

@section('title', 'Edit Laporan - E-Ticketing System')

@section('content')
    <div class="flex min-h-screen bg-[#001D39]">
        <!-- Include sidebar -->
        @include('layouts.sidebar')

        <!-- Konten halaman dengan scroll -->
        <div class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto" style="height: 100vh;">
            <!-- Header -->
            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-white mb-1 sm:mb-2">Edit Laporan</h1>
                <p class="text-sm sm:text-base text-gray-400">Nomor Ticket: <span
                        class="text-blue-400 font-mono break-all">{{ $laporan->nomor_ticket }}</span></p>
            </div>

            <!-- Alert untuk error/success -->
            @if (session('error'))
                <div
                    class="mb-4 bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg text-sm sm:text-base">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div
                    class="mb-4 bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg text-sm sm:text-base">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tampilkan error validasi -->
            @if ($errors->any())
                <div
                    class="mb-4 bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg text-sm sm:text-base">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl p-4 sm:p-6">
                <form action="{{ route('laporan.update', $laporan->id) }}" method="POST" enctype="multipart/form-data"
                    id="editLaporanForm">
                    @csrf
                    @method('PUT')

                    <!-- Grid 2 kolom untuk input yang berpasangan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                        <!-- Nama Pelapor (READONLY) -->
                        <div>
                            <label for="nama_pelapor"
                                class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                                Nama Pelapor <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <input type="text" id="nama_pelapor" name="nama_pelapor"
                                    value="{{ old('nama_pelapor', $laporan->nama_pelapor) }}"
                                    class="w-full bg-white/5 border border-white/10 text-white rounded-lg pl-10 pr-4 py-2.5 sm:py-3 focus:border-green-400 focus:outline-none transition-colors cursor-not-allowed text-sm sm:text-base"
                                    placeholder="Masukkan nama lengkap" readonly>
                            </div>
                            @error('nama_pelapor')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- No Handphone (READONLY) -->
                        <div>
                            <label for="no_handphone"
                                class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                                No. Handphone <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                </div>
                                <input type="tel" id="no_handphone" name="no_handphone"
                                    value="{{ old('no_handphone', $laporan->no_handphone) }}"
                                    class="w-full bg-white/5 border border-white/10 text-white rounded-lg pl-10 pr-4 py-2.5 sm:py-3 focus:border-green-400 focus:outline-none transition-colors cursor-not-allowed text-sm sm:text-base"
                                    placeholder="08xxxxxxxxxx" readonly>
                            </div>
                            @error('no_handphone')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kantor (READONLY) -->
                        <div>
                            <label for="kantor_id" class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                                Kantor <span class="text-red-400">*</span>
                            </label>
                            <input type="hidden" name="kantor_id" value="{{ $laporan->kantor_id }}">
                            <div class="relative">
                                <select id="kantor_id_display"
                                    class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 sm:py-3 appearance-none focus:border-green-400 focus:outline-none transition-colors cursor-not-allowed text-sm sm:text-base"
                                    disabled>
                                    <option value="{{ $laporan->kantor_id }}" selected>
                                        {{ $laporan->kantor->kode_cabang }} - {{ $laporan->kantor->nama_cabang }}
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Jenis Aplikasi (READONLY) -->
                        <div>
                            <label for="jenis_aplikasi_id"
                                class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                                Jenis Aplikasi <span class="text-red-400">*</span>
                            </label>
                            <input type="hidden" name="jenis_aplikasi_id" value="{{ $laporan->jenis_aplikasi_id }}">
                            <div class="relative">
                                <select id="jenis_aplikasi_id_display"
                                    class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 sm:py-3 appearance-none focus:border-green-400 focus:outline-none transition-colors cursor-not-allowed text-sm sm:text-base"
                                    disabled>
                                    <option value="{{ $laporan->jenis_aplikasi_id }}" selected>
                                        {{ $laporan->jenisAplikasi->jenis_aplikasi }} -
                                        {{ $laporan->jenisAplikasi->deskripsi }}
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-yellow-400 mt-1 flex items-start gap-1">
                                <svg class="w-3 h-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                                <span>Jenis Aplikasi tidak dapat diubah karena terkait dengan format nomor ticket</span>
                            </p>
                        </div>

                        <!-- Kode Produk (READONLY - tidak bisa diubah) -->
                        <div>
                            <label for="kode_produk_id"
                                class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                                Kode/Nama Produk <span class="text-red-400">*</span>
                            </label>
                            <input type="hidden" name="kode_produk_id" value="{{ $laporan->kode_produk_id }}">
                            <div class="relative">
                                <select id="kode_produk_id_display"
                                    class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 sm:py-3 appearance-none focus:border-green-400 focus:outline-none transition-colors cursor-not-allowed text-sm sm:text-base"
                                    disabled>
                                    <option value="{{ $laporan->kode_produk_id }}" selected>
                                        {{ $laporan->produk->kode_produk ?? '' }} -
                                        {{ $laporan->produk->nama_produk ?? 'N/A' }}
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-yellow-400 mt-1 flex items-start gap-1">
                                <svg class="w-3 h-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                                <span>Produk tidak dapat diubah karena terkait dengan format nomor ticket</span>
                            </p>
                        </div>

                        <!-- Status (EDITABLE) -->
                        <div>
                            <label for="status" class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                                Status <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <select id="status" name="status"
                                    class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 sm:py-3 appearance-none focus:border-green-400 focus:outline-none transition-colors @error('status') border-red-400 @enderror text-sm sm:text-base">
                                    <option value="open" class="bg-[#001D39] text-yellow-400"
                                        {{ $laporan->status == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="process" class="bg-[#001D39] text-blue-400"
                                        {{ $laporan->status == 'process' ? 'selected' : '' }}>Process</option>
                                    <option value="done" class="bg-[#001D39] text-green-400"
                                        {{ $laporan->status == 'done' ? 'selected' : '' }}>Done</option>
                                    <option value="reject" class="bg-[#001D39] text-red-400"
                                        {{ $laporan->status == 'reject' ? 'selected' : '' }}>Reject</option>
                                    <option value="pending" class="bg-[#001D39] text-orange-400"
                                        {{ $laporan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="escalate" class="bg-[#001D39] text-purple-400"
                                        {{ $laporan->status == 'escalate' ? 'selected' : '' }}>Escalate</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('status')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Selesai (READONLY) -->
                        <div id="tanggalSelesaiField" style="{{ $laporan->status == 'done' ? '' : 'display: none;' }}">
                            <label for="tanggal_selesai"
                                class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                                Tanggal Selesai
                            </label>
                            <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                                value="{{ old('tanggal_selesai', $laporan->tanggal_selesai ? $laporan->tanggal_selesai->format('Y-m-d') : '') }}"
                                class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 sm:py-3 focus:border-green-400 focus:outline-none transition-colors cursor-not-allowed text-sm sm:text-base"
                                readonly>
                            @error('tanggal_selesai')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kronologi (READONLY) -->
                    <div class="mt-4 sm:mt-5">
                        <label for="kronologi" class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                            Kronologi <span class="text-red-400">*</span>
                        </label>
                        <input type="hidden" name="kronologi" value="{{ $laporan->kronologi }}">
                        <div class="relative">
                            <div class="absolute top-3 left-3 pointer-events-none">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h7"></path>
                                </svg>
                            </div>
                            <textarea id="kronologi_display" rows="4"
                                class="w-full bg-white/5 border border-white/10 text-white rounded-lg pl-10 pr-4 py-2.5 sm:py-3 focus:border-green-400 focus:outline-none transition-colors cursor-not-allowed text-sm sm:text-base"
                                readonly disabled>{{ old('kronologi', $laporan->kronologi) }}</textarea>
                        </div>
                    </div>

                    <!-- Solusi (EDITABLE) -->
                    <div class="mt-4 sm:mt-5">
                        <label for="solusi" class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                            Solusi
                        </label>
                        <textarea id="solusi" name="solusi" rows="3"
                            class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 sm:py-3 focus:border-green-400 focus:outline-none transition-colors @error('solusi') border-red-400 @enderror text-sm sm:text-base"
                            placeholder="Masukkan solusi (opsional)">{{ old('solusi', $laporan->solusi) }}</textarea>
                        @error('solusi')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pending Deskripsi Field -->
                    <div class="mt-4 sm:mt-5" id="pendingDeskripsiField" style="display: none;">
                        <label for="pending_deskripsi"
                            class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                            Alasan Pending <span class="text-red-400">*</span>
                        </label>
                        <textarea id="pending_deskripsi" name="pending_deskripsi" rows="3"
                            class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 sm:py-3 focus:border-orange-400 focus:outline-none transition-colors @error('pending_deskripsi') border-red-400 @enderror text-sm sm:text-base"
                            placeholder="Masukkan alasan mengapa laporan ini dipending...">{{ old('pending_deskripsi', $laporan->pending_deskripsi) }}</textarea>
                        @error('pending_deskripsi')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Escalate Deskripsi Field -->
                    <div class="mt-4 sm:mt-5" id="escalateDeskripsiField" style="display: none;">
                        <label for="escalate_deskripsi"
                            class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                            Alasan Escalate <span class="text-red-400">*</span>
                        </label>
                        <textarea id="escalate_deskripsi" name="escalate_deskripsi" rows="3"
                            class="w-full bg-white/5 border border-white/10 text-white rounded-lg px-4 py-2.5 sm:py-3 focus:border-purple-400 focus:outline-none transition-colors @error('escalate_deskripsi') border-red-400 @enderror text-sm sm:text-base"
                            placeholder="Masukkan alasan mengapa laporan ini di-escalate...">{{ old('escalate_deskripsi', $laporan->escalate_deskripsi) }}</textarea>
                        @error('escalate_deskripsi')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Multiple Lampiran dengan preview existing -->
                    <div class="mt-4 sm:mt-5">
                        <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-2">
                            Lampiran
                        </label>

                        <!-- Existing Files Preview -->
                        @if ($laporan->lampiran && count($laporan->lampiran) > 0)
                            <div class="mb-4">
                                <p class="text-xs sm:text-sm text-gray-400 mb-2">File yang sudah ada
                                    ({{ count($laporan->lampiran) }} file):</p>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 sm:gap-3"
                                    id="existing-files">
                                    @foreach ($laporan->lampiran as $index => $file)
                                        @php
                                            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                            $fileName = basename($file);
                                            $fileUrl = Storage::url($file);
                                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp']);
                                        @endphp
                                        <div class="existing-file-item relative group bg-white/5 border border-white/10 rounded-lg p-2 hover:border-green-500/50 transition-all duration-300"
                                            data-file="{{ $file }}">
                                            @if ($isImage)
                                                <img src="{{ $fileUrl }}" alt="{{ $fileName }}"
                                                    class="w-full h-16 sm:h-20 object-cover rounded-lg mb-2">
                                            @else
                                                <div class="h-16 sm:h-20 flex items-center justify-center mb-2">
                                                    @if ($extension == 'pdf')
                                                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-red-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                    @elseif(in_array($extension, ['doc', 'docx']))
                                                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-blue-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                            </path>
                                                        </svg>
                                                    @elseif(in_array($extension, ['xls', 'xlsx']))
                                                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-green-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                            </path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                    @endif
                                                </div>
                                            @endif
                                            <p class="text-xs text-gray-300 truncate text-center"
                                                title="{{ $fileName }}">
                                                {{ $fileName }}
                                            </p>
                                            <div
                                                class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg">
                                                <div class="flex gap-2">
                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                        class="text-xs text-white bg-blue-600 hover:bg-blue-700 px-2 py-1 rounded-full transition-colors">
                                                        Lihat
                                                    </a>
                                                    <button type="button"
                                                        onclick="markFileForDeletion(this, '{{ $file }}')"
                                                        class="text-xs text-white bg-red-600 hover:bg-red-700 px-2 py-1 rounded-full transition-colors">
                                                        Hapus
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="deleted_files" id="deleted_files" value="[]">
                            </div>
                        @endif

                        <!-- Upload New Files -->
                        <div class="mt-4">
                            <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-2">
                                Tambah Lampiran Baru
                            </label>
                            <div id="dropzone-area"
                                class="relative border-2 border-dashed border-white/10 rounded-lg p-3 sm:p-4 hover:border-green-500/50 transition-all duration-300 cursor-pointer group">
                                <input type="file" id="lampiran" name="lampiran[]" multiple
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">

                                <div id="preview-container" class="hidden mb-2">
                                    <div id="files-preview" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2">
                                    </div>
                                </div>

                                <div id="upload-placeholder"
                                    class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-3 text-center sm:text-left">
                                    <svg class="h-6 w-6 sm:h-8 sm:w-8 text-gray-400 group-hover:text-green-400 transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    <div>
                                        <p class="text-xs sm:text-sm text-gray-400">
                                            <span class="text-green-400 font-semibold">Klik untuk upload</span> atau drag
                                            and drop
                                        </p>
                                        <p class="text-[10px] sm:text-xs text-gray-500 mt-1">JPG, PNG, PDF, DOC (Max. 2MB
                                            each)</p>
                                    </div>
                                    <span id="file-count" class="text-xs text-green-400 sm:ml-auto"></span>
                                </div>
                            </div>
                        </div>
                        @error('lampiran.*')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-400 mt-2">
                            <span class="text-yellow-400">*</span> File yang dihapus akan dihapus secara permanen.
                        </p>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row items-center gap-3 mt-6 sm:mt-8 pt-4 border-t border-white/10">
                        <button type="submit" id="submitBtn"
                            class="w-full sm:w-auto group relative px-6 sm:px-8 py-2.5 sm:py-3 overflow-hidden rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-2xl hover:shadow-blue-600/50 text-sm sm:text-base">
                            <span class="relative z-10 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Laporan
                            </span>
                            <div
                                class="absolute inset-0 bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.1)_50%,transparent_75%)] translate-x-[-200%] group-hover:translate-x-[200%] transition-transform duration-1000">
                            </div>
                        </button>
                        <a href="{{ route('laporan.index') }}"
                            class="w-full sm:w-auto px-6 py-2.5 sm:py-3 border border-white/10 text-gray-300 rounded-xl hover:bg-white/5 transition-colors text-center text-sm sm:text-base">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom scrollbar */
        .overflow-y-auto::-webkit-scrollbar {
            width: 5px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: rgba(16, 185, 129, 0.3);
            border-radius: 10px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: rgba(16, 185, 129, 0.6);
        }

        .existing-file-item {
            position: relative;
            transition: all 0.3s ease;
        }

        .existing-file-item:hover {
            border-color: #10b981;
            transform: scale(1.02);
        }

        input[readonly],
        select[readonly],
        textarea[readonly],
        select:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

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

        /* Mobile touch improvement for remove button */
        @media (max-width: 640px) {
            .preview-item .remove-file {
                opacity: 1;
                width: 22px;
                height: 22px;
            }

            .preview-item .remove-file svg {
                width: 12px;
                height: 12px;
            }

            .existing-file-item .absolute {
                opacity: 1;
                background: rgba(0, 0, 0, 0.7);
            }
        }

        #dropzone-area.dragover {
            border-color: #10b981;
            background: rgba(16, 185, 129, 0.1);
        }

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

        /* Word break for long text */
        .break-all {
            word-break: break-all;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let selectedFiles = [];
        let deletedFiles = [];

        // Mark file for deletion
        window.markFileForDeletion = function(button, filePath) {
            const fileItem = button.closest('.existing-file-item');
            if (fileItem) {
                fileItem.style.opacity = '0.5';
                fileItem.style.backgroundColor = 'rgba(239, 68, 68, 0.1)';
                const deleteBtn = button;
                deleteBtn.disabled = true;
                deleteBtn.textContent = 'Terhapus';
                deleteBtn.classList.remove('bg-red-600', 'hover:bg-red-700');
                deleteBtn.classList.add('bg-gray-600', 'cursor-not-allowed');

                deletedFiles.push(filePath);
                document.getElementById('deleted_files').value = JSON.stringify(deletedFiles);
            }
        };

        // File upload handling
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
                    const fileSize = (file.size / 1024).toFixed(2);
                    const fileName = file.name.length > 12 ? file.name.substring(0, 10) + '...' : file.name;

                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewItem.innerHTML = `
                                <img src="${e.target.result}" alt="Preview">
                                <div class="file-info">
                                    <p class="truncate" title="${file.name}">${fileName}</p>
                                    <p class="text-[8px] opacity-75">${fileSize} KB</p>
                                </div>
                                <button type="button" class="remove-file" onclick="removeNewFile(${index})">
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
                                '<svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>';
                            bgColor = 'bg-red-500/10';
                        } else if (file.type.includes('word') || file.name.match(/\.docx?$/)) {
                            icon =
                                '<svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
                            bgColor = 'bg-blue-500/10';
                        } else {
                            icon =
                                '<svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>';
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
                            <button type="button" class="remove-file" onclick="removeNewFile(${index})">
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

        window.removeNewFile = function(index) {
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

        // Form validation before submit
        document.getElementById('editLaporanForm').addEventListener('submit', function(e) {
            const status = document.getElementById('status').value;
            if (status === 'done') {
                const solusi = document.getElementById('solusi').value.trim();
                if (solusi === '') {
                    if (!confirm('Status "Done" dipilih tetapi solusi kosong. Lanjutkan?')) {
                        e.preventDefault();
                    }
                }
            }
        });

        // Toggle fields berdasarkan status
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const tanggalSelesaiField = document.getElementById('tanggalSelesaiField');
            const pendingDeskripsiField = document.getElementById('pendingDeskripsiField');
            const escalateDeskripsiField = document.getElementById('escalateDeskripsiField');

            function toggleFields() {
                const status = statusSelect.value;

                // Tanggal Selesai untuk status done
                if (status === 'done') {
                    tanggalSelesaiField.style.display = 'block';
                } else {
                    tanggalSelesaiField.style.display = 'none';
                }

                // Pending Deskripsi untuk status pending
                if (status === 'pending') {
                    pendingDeskripsiField.style.display = 'block';
                    document.getElementById('pending_deskripsi').setAttribute('required', 'required');
                } else {
                    pendingDeskripsiField.style.display = 'none';
                    document.getElementById('pending_deskripsi').removeAttribute('required');
                }

                // Escalate Deskripsi untuk status escalate
                if (status === 'escalate') {
                    escalateDeskripsiField.style.display = 'block';
                    document.getElementById('escalate_deskripsi').setAttribute('required', 'required');
                } else {
                    escalateDeskripsiField.style.display = 'none';
                    document.getElementById('escalate_deskripsi').removeAttribute('required');
                }
            }

            // Initial toggle
            toggleFields();

            // Toggle on change
            statusSelect.addEventListener('change', toggleFields);
        });
    </script>
@endpush
