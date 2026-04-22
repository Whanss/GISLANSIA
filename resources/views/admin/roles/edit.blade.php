@extends('layouts.app')

@section('title', 'Edit Peran')
@section('page-title', 'Edit Peran')

@section('content')
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Header --}}
        <div class="bg-white border-b border-gray-200 px-8 py-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('roles.index') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Peran</h1>
                    <p class="text-gray-600 text-sm mt-1">Ubah nama dan izin peran</p>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="flex-1 overflow-auto">
            <div class="p-8 max-w-2xl">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Nama Peran --}}
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-900 mb-2">
                                Nama Peran <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $role->name) }}"
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
                                @php
                                    $permissions = \Spatie\Permission\Models\Permission::all();
                                    $rolePermissions = $role->permissions->pluck('id')->toArray();
                                @endphp
                                @forelse($permissions as $permission)
                                    <div class="flex items-center">
                                        <input type="checkbox" id="permission_{{ $permission->id }}" name="permissions[]"
                                            value="{{ $permission->id }}" @if (in_array($permission->id, $rolePermissions)) checked @endif
                                            class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                                        <label for="permission_{{ $permission->id }}" class="ml-3 text-sm text-gray-700">
                                            <span class="font-medium">{{ $permission->name }}</span>
                                            <span
                                                class="text-gray-500 text-xs ml-2">{{ $permission->description ?? '' }}</span>
                                        </label>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-sm">Tidak ada izin tersedia</p>
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
                                Simpan Perubahan
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
