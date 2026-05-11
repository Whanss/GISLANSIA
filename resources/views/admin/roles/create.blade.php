@extends('layouts.app')

@section('title', 'Tambah Peran Baru')
@section('page-title', 'Tambah Peran Baru')

@section('content')
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Content --}}
        <div class="flex-1 overflow-auto">
            <div class="p-8 max-w-2xl">
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf

                    {{-- Nama Peran --}}
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-900 mb-2">
                            Nama Peran <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                            placeholder="Contoh: Editor, Moderator">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Izin --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-900 mb-3">
                            Izin <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            @forelse($permissions as $permission)
                                <div class="flex items-center">
                                    <input type="checkbox" id="permission_{{ $permission->id }}" name="permissions[]"
                                        value="{{ $permission->id }}"
                                        class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                                    <label for="permission_{{ $permission->id }}" class="ml-3 text-sm text-gray-700">
                                        <span class="font-medium">{{ $permission->name }}</span>
                                    </label>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">Tidak ada izin tersedia. <a href="{{ route('permissions.index') }}" class="text-blue-600 hover:underline">Tambah izin</a></p>
                            @endforelse
                        </div>
                        @error('permissions')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="mt-8 flex gap-3">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Simpan Peran
                        </button>
                        <a href="{{ route('roles.index') }}"
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
