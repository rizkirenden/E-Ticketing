@extends('layouts.app')

@section('title', 'Detail Activity Log - E-Ticketing System')

@section('content')
    <div class="flex min-h-screen bg-[#001D39]">
        <!-- Include sidebar -->
        @include('layouts.sidebar')

        <!-- Konten halaman -->
        <div class="flex-1 p-8 overflow-y-auto">
            <!-- Header -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Detail Activity Log</h1>
                    <p class="text-gray-400">Informasi lengkap aktivitas pengguna</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('activity-logs.export-pdf-single', $log->id) }}?orientation=portrait" target="_blank"
                        class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export PDF
                    </a>
                    <a href="{{ route('activity-logs.index') }}"
                        class="px-4 py-2.5 border border-white/10 text-gray-300 rounded-lg hover:bg-white/5 transition-colors text-sm">
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Detail Card -->
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- ID Log -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">ID Log</label>
                        <p class="text-white font-mono">#{{ $log->id }}</p>
                    </div>

                    <!-- User -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">User</label>
                        @if ($log->user)
                            <p class="text-white">{{ $log->user->nama }}</p>
                            <p class="text-xs text-gray-400">{{ $log->user->username }}</p>
                        @else
                            <p class="text-white">System</p>
                        @endif
                    </div>

                    <!-- Aktivitas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Aktivitas</label>
                        @php
                            $activityColors = [
                                'CREATE' => 'bg-green-500/20 text-green-400',
                                'UPDATE' => 'bg-blue-500/20 text-blue-400',
                                'DELETE' => 'bg-red-500/20 text-red-400',
                                'LOGIN' => 'bg-purple-500/20 text-purple-400',
                                'LOGOUT' => 'bg-yellow-500/20 text-yellow-400',
                                'LOGIN_FAILED' => 'bg-orange-500/20 text-orange-400',
                                'WHATSAPP' => 'bg-emerald-500/20 text-emerald-400',
                            ];
                            $activityColor = $activityColors[$log->aktivitas] ?? 'bg-gray-500/20 text-gray-400';
                        @endphp
                        <span class="{{ $activityColor }} px-3 py-1.5 rounded-lg text-sm font-medium inline-block">
                            {{ $log->aktivitas }}
                        </span>
                    </div>

                    <!-- Modul -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Modul</label>
                        <span class="bg-white/5 text-gray-300 px-3 py-1.5 rounded-lg text-sm inline-block">
                            {{ $log->modul }}
                        </span>
                    </div>

                    <!-- Data ID -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Data ID</label>
                        <p class="text-white font-mono">{{ $log->data_id ?? '-' }}</p>
                    </div>

                    <!-- Tanggal Aktivitas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Tanggal Aktivitas</label>
                        <p class="text-white">{{ $log->tanggal_aktivitas->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Deskripsi</label>
                    <div class="bg-white/5 border border-white/10 rounded-lg p-4 text-white">
                        {{ $log->deskripsi }}
                    </div>
                </div>

                <!-- Data Sebelum -->
                @if ($log->data_sebelum)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-400 mb-2">Data Sebelum</label>
                        <div class="bg-[#0a1a2f] border border-white/10 rounded-lg p-4 overflow-x-auto">
                            <pre class="text-sm text-gray-300 font-mono">{{ json_encode($log->data_sebelum, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                @endif

                <!-- Data Sesudah -->
                @if ($log->data_sesudah)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-400 mb-2">Data Sesudah</label>
                        <div class="bg-[#0a1a2f] border border-white/10 rounded-lg p-4 overflow-x-auto">
                            <pre class="text-sm text-gray-300 font-mono">{{ json_encode($log->data_sesudah, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                @endif

                <!-- Metadata -->
                <div class="border-t border-white/10 pt-4 mt-6">
                    <div class="flex gap-6 text-xs text-gray-400">
                        <div>Dibuat: {{ $log->created_at->format('d/m/Y H:i:s') }}</div>
                        <div>Diupdate: {{ $log->updated_at->format('d/m/Y H:i:s') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
