{{-- resources/views/role_permissions/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Permission - E-Ticketing System')

@section('content')
    <div class="flex min-h-screen bg-[#001D39]">
        @include('layouts.sidebar')

        <div class="flex-1 p-6 overflow-y-auto">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Tambah Permission</h1>
                    <p class="text-gray-400">Atur hak akses untuk role tertentu</p>
                </div>
                <a href="{{ route('role-permissions.index') }}"
                    class="px-4 py-2 border border-white/10 text-gray-300 rounded-lg hover:bg-white/5 transition-colors">
                    Kembali
                </a>
            </div>

            <form action="{{ route('role-permissions.store') }}" method="POST">
                @csrf

                <!-- Pilih Role -->
                <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-6 mb-6">
                    <h2 class="text-lg font-semibold text-white mb-4">Pilih Role</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($roles as $role)
                            <label
                                class="relative flex items-center p-4 bg-white/5 rounded-lg border border-white/10 hover:bg-white/10 cursor-pointer">
                                <input type="radio" name="role_id" value="{{ $role->id }}"
                                    class="w-4 h-4 text-blue-600 bg-white/5 border-white/20 focus:ring-blue-500"
                                    {{ old('role_id') == $role->id ? 'checked' : '' }} required>
                                <span class="ml-3 text-white">{{ $role->nama_role }}</span>
                                @if ($role->nama_role === 'SUPERADMIN')
                                    <span
                                        class="absolute top-2 right-2 text-xs bg-yellow-500/20 text-yellow-400 px-2 py-1 rounded">Full
                                        Access</span>
                                @endif
                            </label>
                        @endforeach
                    </div>

                    @error('role_id')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Permission Settings -->
                <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-6">
                    <h2 class="text-lg font-semibold text-white mb-4">Hak Akses per Menu</h2>

                    <div class="space-y-4">
                        @foreach ($menus as $menuKey => $menuLabel)
                            <div class="border border-white/10 rounded-lg overflow-hidden">
                                <div class="bg-white/5 px-4 py-3 border-b border-white/10">
                                    <h3 class="text-white font-medium">{{ $menuLabel }}</h3>
                                </div>
                                <div class="p-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                    @foreach ($actions as $actionKey => $actionLabel)
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox"
                                                name="permissions[{{ $menuKey }}][{{ $actionKey }}]" value="1"
                                                class="w-4 h-4 text-blue-600 bg-white/5 border-white/20 rounded focus:ring-blue-500">
                                            <span class="text-gray-300 text-sm">{{ $actionLabel }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('role-permissions.index') }}"
                            class="px-6 py-2.5 border border-white/10 text-gray-300 rounded-lg hover:bg-white/5 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Simpan Permission
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
