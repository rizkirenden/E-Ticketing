@extends('layouts.app')

@section('title', 'Public Laporan - Tracking System')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-[#001D39] via-[#002B4F] to-[#001D39]">
        <!-- Hero Section -->
        <div class="relative overflow-hidden">
            <!-- Animated Background with Logos -->
            <div class="absolute inset-0 opacity-10">
                <!-- Logo besar di berbagai posisi dengan animasi berbeda -->
                <div class="absolute top-0 -left-4 w-72 h-72 animate-float-slow">
                    <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo"
                        class="w-full h-full object-contain brightness-0 invert">
                </div>
                <div class="absolute top-1/3 -right-4 w-64 h-64 animate-float">
                    <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo"
                        class="w-full h-full object-contain brightness-0 invert opacity-70">
                </div>
                <div class="absolute bottom-0 left-1/4 w-80 h-80 animate-float-delayed">
                    <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo"
                        class="w-full h-full object-contain brightness-0 invert opacity-50">
                </div>
                <div class="absolute top-1/2 left-10 w-48 h-48 animate-spin-slow">
                    <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo"
                        class="w-full h-full object-contain brightness-0 invert opacity-30">
                </div>
                <div class="absolute bottom-1/4 right-10 w-56 h-56 animate-bounce-slow">
                    <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo"
                        class="w-full h-full object-contain brightness-0 invert opacity-40">
                </div>
                <!-- Additional small logos -->
                <div class="absolute top-20 right-1/4 w-32 h-32 animate-pulse-slow">
                    <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo"
                        class="w-full h-full object-contain brightness-0 invert opacity-20">
                </div>
                <div class="absolute bottom-40 left-20 w-40 h-40 animate-float-reverse">
                    <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo"
                        class="w-full h-full object-contain brightness-0 invert opacity-25">
                </div>
                <!-- Tambahan logo kecil untuk memenuhi ruang -->
                <div class="absolute top-40 left-1/3 w-24 h-24 animate-spin-slow opacity-15">
                    <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo"
                        class="w-full h-full object-contain brightness-0 invert">
                </div>
                <div class="absolute bottom-32 right-1/3 w-28 h-28 animate-float opacity-20">
                    <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo"
                        class="w-full h-full object-contain brightness-0 invert">
                </div>
            </div>

            <!-- Header with Logo and Credit - Paling Ujung -->
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <!-- Logo di Ujung Kiri -->
                    <div class="flex items-center group">
                        <img src="{{ asset('assets/logo1.PNG') }}" alt="Logo E-Ticketing"
                            class="h-13 w-auto brightness-0 invert drop-shadow-2xl transform group-hover:scale-110 transition-transform duration-500"
                            onerror="this.style.display='none'">
                    </div>

                    <!-- Credit di Ujung Kanan -->
                    <div class="flex items-center space-x-2">
                        <span class="text-white font-medium">DEVELOP BY</span>
                        <span
                            class="bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent font-bold text-lg">
                            IT DIGITAL
                        </span>
                    </div>
                </div>
            </div>

            <!-- Hero Content - Tetap di Tengah -->
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center">
                    <h1 class="text-5xl font-bold text-white mb-4 animate-fade-in">
                        <span class="bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                            Laporan Publik
                        </span>
                    </h1>
                    <p class="text-xl text-gray-300 mb-8 max-w-3xl mx-auto">
                        Lihat progres dan detail laporan yang telah ditangani oleh tim IT
                    </p>

                    <!-- Search & Track Card -->
                    <div class="max-w-2xl mx-auto">
                        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-2xl">
                            <form action="{{ route('detail_laporan.track') }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="relative">
                                    <input type="text" name="nomor_ticket"
                                        placeholder="Masukkan nomor tiket (contoh: DIG01-001-TCK-27032026-0001)"
                                        class="w-full bg-white/10 border border-white/20 text-white placeholder-gray-400 rounded-xl px-6 py-4 pl-14 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400/20 transition-all text-lg">
                                    <svg class="w-6 h-6 text-gray-400 absolute left-4 top-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <button type="submit"
                                    class="w-full bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white font-semibold py-4 px-6 rounded-xl transition-all transform hover:scale-[1.02] hover:shadow-xl">
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                        Lacak Laporan
                                    </span>
                                </button>
                            </form>

                            <!-- Back to Home Button - Dengan desain sama seperti tombol lacak -->
                            <div class="mt-4">
                                <a href="/"
                                    class="w-full bg-white/5 hover:bg-white/10 border border-white/10 text-white font-semibold py-4 px-6 rounded-xl transition-all transform hover:scale-[1.02] hover:shadow-xl flex items-center justify-center gap-2 group">
                                    <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    <span>Kembali ke Beranda</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            @keyframes fade-in {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes float {

                0%,
                100% {
                    transform: translateY(0px) rotate(0deg);
                }

                50% {
                    transform: translateY(-20px) rotate(5deg);
                }
            }

            @keyframes float-slow {

                0%,
                100% {
                    transform: translateY(0px) rotate(0deg);
                }

                50% {
                    transform: translateY(-30px) rotate(-5deg);
                }
            }

            @keyframes float-delayed {

                0%,
                100% {
                    transform: translateY(0px) rotate(0deg);
                }

                50% {
                    transform: translateY(-25px) rotate(10deg);
                }
            }

            @keyframes float-reverse {

                0%,
                100% {
                    transform: translateY(0px) rotate(0deg);
                }

                50% {
                    transform: translateY(15px) rotate(-8deg);
                }
            }

            @keyframes spin-slow {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }

            @keyframes bounce-slow {

                0%,
                100% {
                    transform: translateY(0) scale(1);
                }

                50% {
                    transform: translateY(-15px) scale(1.05);
                }
            }

            @keyframes pulse-slow {

                0%,
                100% {
                    opacity: 0.2;
                    transform: scale(1);
                }

                50% {
                    opacity: 0.4;
                    transform: scale(1.1);
                }
            }

            .animate-fade-in {
                animation: fade-in 0.8s ease-out;
            }

            .animate-float {
                animation: float 6s ease-in-out infinite;
            }

            .animate-float-slow {
                animation: float-slow 8s ease-in-out infinite;
            }

            .animate-float-delayed {
                animation: float-delayed 7s ease-in-out infinite;
            }

            .animate-float-reverse {
                animation: float-reverse 5s ease-in-out infinite;
            }

            .animate-spin-slow {
                animation: spin-slow 20s linear infinite;
            }

            .animate-bounce-slow {
                animation: bounce-slow 4s ease-in-out infinite;
            }

            .animate-pulse-slow {
                animation: pulse-slow 3s ease-in-out infinite;
            }

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

            /* Styling untuk input date */
            input[type="date"]::-webkit-calendar-picker-indicator {
                filter: invert(1);
                opacity: 0.5;
                cursor: pointer;
            }

            input[type="date"]::-webkit-calendar-picker-indicator:hover {
                opacity: 1;
            }

            /* Tambahkan di bagian style */
            html,
            body {
                overflow: auto !important;
                height: 100%;
                margin: 0;
                padding: 0;
            }

            .min-h-screen {
                min-height: 100vh;
                overflow-y: auto !important;
                overflow-x: hidden;
            }

            /* ============================================ */
            /* RESPONSIVE UNTUK MOBILE SAJA (max-width: 768px) */
            /* TIDAK MENGUBAH TAMPILAN PC/LAPTOP */
            /* ============================================ */
            @media (max-width: 768px) {

                /* Header padding lebih kecil */
                .py-4 {
                    padding-top: 0.75rem !important;
                    padding-bottom: 0.75rem !important;
                }

                /* Logo header lebih kecil */
                .h-13 {
                    height: 2rem !important;
                }

                /* Credit text lebih kecil */
                .text-lg {
                    font-size: 0.875rem !important;
                }

                .font-medium {
                    font-size: 0.7rem !important;
                }

                .space-x-2 {
                    margin-left: 0.5rem !important;
                }

                /* Judul lebih kecil */
                .text-5xl {
                    font-size: 2rem !important;
                    line-height: 2.5rem !important;
                }

                /* Deskripsi lebih kecil */
                .text-xl {
                    font-size: 1rem !important;
                    line-height: 1.5rem !important;
                }

                /* Card padding lebih kecil */
                .p-6 {
                    padding: 1rem !important;
                }

                /* Input field lebih kecil */
                .px-6.py-4 {
                    padding: 0.75rem 1rem !important;
                }

                .pl-14 {
                    padding-left: 2.5rem !important;
                }

                .text-lg {
                    font-size: 0.875rem !important;
                }

                /* Tombol lebih kecil */
                .py-4.px-6 {
                    padding: 0.75rem 1rem !important;
                }

                /* Icon di input lebih kecil */
                .w-6.h-6 {
                    width: 1.25rem !important;
                    height: 1.25rem !important;
                }

                .left-4 {
                    left: 0.75rem !important;
                }

                .top-4 {
                    top: 0.75rem !important;
                }

                .w-5.h-5 {
                    width: 1rem !important;
                    height: 1rem !important;
                }

                /* Hero section padding */
                .py-8 {
                    padding-top: 1.5rem !important;
                    padding-bottom: 1.5rem !important;
                }

                /* Margin bottom */
                .mb-4 {
                    margin-bottom: 0.75rem !important;
                }

                .mb-8 {
                    margin-bottom: 1rem !important;
                }

                /* Jarak antar elemen form */
                .space-y-4 {
                    margin-top: 0.75rem !important;
                }

                /* Back to home button margin */
                .mt-4 {
                    margin-top: 0.75rem !important;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            let searchTimeout;
            const searchInput = document.getElementById('searchInput');
            const filterKantor = document.getElementById('filterKantor');
            const filterAplikasi = document.getElementById('filterAplikasi');
            const filterStatus = document.getElementById('filterStatus');
            const tanggalAwal = document.getElementById('tanggalAwal');
            const tanggalAkhir = document.getElementById('tanggalAkhir');
            const tableContainer = document.getElementById('table-container');

            function fetchLaporans() {
                const search = searchInput ? searchInput.value : '';
                const kantor = filterKantor ? filterKantor.value : '';
                const aplikasi = filterAplikasi ? filterAplikasi.value : '';
                const status = filterStatus ? filterStatus.value : '';
                const tglAwal = tanggalAwal ? tanggalAwal.value : '';
                const tglAkhir = tanggalAkhir ? tanggalAkhir.value : '';

                // Validasi tanggal
                if (tglAwal && tglAkhir && tglAwal > tglAkhir) {
                    alert('Tanggal akhir harus lebih besar atau sama dengan tanggal awal');
                    return;
                }

                if (tableContainer) {
                    tableContainer.innerHTML =
                        '<div class="text-center py-20"><div class="loading mx-auto"></div><p class="text-gray-400 mt-4">Loading...</p></div>';
                }

                const url = new URL(window.location.href);
                url.searchParams.set('search', search);
                url.searchParams.set('kantor', kantor);
                url.searchParams.set('aplikasi', aplikasi);
                url.searchParams.set('status', status);
                url.searchParams.set('tanggal_awal', tglAwal);
                url.searchParams.set('tanggal_akhir', tglAkhir);

                // Update URL without reload
                window.history.pushState({}, '', url);

                fetch(url.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        if (tableContainer) {
                            tableContainer.innerHTML = html;
                            attachPaginationListeners();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (tableContainer) {
                            tableContainer.innerHTML =
                                '<div class="text-center py-20 text-red-400">Error loading data</div>';
                        }
                    });
            }

            function attachPaginationListeners() {
                document.querySelectorAll('.pagination-link').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = new URL(this.href);

                        // Preserve filter values
                        if (searchInput) url.searchParams.set('search', searchInput.value);
                        if (filterKantor) url.searchParams.set('kantor', filterKantor.value);
                        if (filterAplikasi) url.searchParams.set('aplikasi', filterAplikasi.value);
                        if (filterStatus) url.searchParams.set('status', filterStatus.value);
                        if (tanggalAwal) url.searchParams.set('tanggal_awal', tanggalAwal.value);
                        if (tanggalAkhir) url.searchParams.set('tanggal_akhir', tanggalAkhir.value);

                        if (tableContainer) {
                            tableContainer.innerHTML =
                                '<div class="text-center py-20"><div class="loading mx-auto"></div><p class="text-gray-400 mt-4">Loading...</p></div>';
                        }

                        fetch(url.toString(), {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.text())
                            .then(html => {
                                if (tableContainer) {
                                    tableContainer.innerHTML = html;
                                    attachPaginationListeners();
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                if (tableContainer) {
                                    tableContainer.innerHTML =
                                        '<div class="text-center py-20 text-red-400">Error loading data</div>';
                                }
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
            if (tanggalAwal && tanggalAkhir) {
                tanggalAwal.addEventListener('change', function() {
                    tanggalAkhir.min = this.value;
                    if (tanggalAkhir.value && tanggalAkhir.value < this.value) {
                        tanggalAkhir.value = this.value;
                    }
                });
            }

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
        </script>
    @endpush
@endsection
