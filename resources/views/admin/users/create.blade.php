@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')
@section('page-title', 'Tambah Pengguna Baru')

@section('content')
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Content --}}
        <div class="flex-1 overflow-auto">
            <div class="p-8 max-w-2xl">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    {{-- Nama --}}
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-900 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                            placeholder="Masukkan nama lengkap">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-900 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                            placeholder="Masukkan alamat email">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-900 mb-2">
                            Kata Sandi <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password" name="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                            placeholder="Masukkan kata sandi (minimal 8 karakter)">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Confirmation --}}
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-900 mb-2">
                            Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Konfirmasi kata sandi">
                    </div>

                    {{-- Peran --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-900 mb-3">
                            Peran <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            @forelse($roles as $role)
                                <div class="flex items-center">
                                    <input type="radio" id="role_{{ $role->id }}" name="role"
                                        value="{{ $role->name }}" @if (old('role') === $role->name) checked @endif
                                        class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                    <label for="role_{{ $role->id }}" class="ml-3 text-sm text-gray-700">
                                        <span class="font-medium capitalize">{{ $role->name }}</span>
                                    </label>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">Tidak ada peran tersedia</p>
                            @endforelse
                        </div>
                        @error('role')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="mt-8 flex gap-3">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Simpan Pengguna
                        </button>
                        <a href="{{ route('users.index') }}"
                            class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
