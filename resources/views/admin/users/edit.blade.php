@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Header --}}
        <div class="bg-white border-b border-gray-200 px-8 py-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('users.index') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left"></i>
                </a>

            </div>
        </div>

        {{-- Content --}}
        <div class="flex-1 overflow-auto">
            <div class="p-8 max-w-2xl">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Nama --}}
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-900 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
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
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                                placeholder="Masukkan alamat email">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password (Optional) --}}
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-gray-900 mb-2">
                                Kata Sandi (Kosongkan jika tidak ingin mengubah)
                            </label>
                            <input type="password" id="password" name="password"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                                placeholder="Masukkan kata sandi baru (minimal 8 karakter)">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Confirmation --}}
                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-900 mb-2">
                                Konfirmasi Kata Sandi
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Konfirmasi kata sandi baru">
                        </div>

                        {{-- Buttons --}}
                        <div class="mt-8 flex gap-3">
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                Simpan Perubahan
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
