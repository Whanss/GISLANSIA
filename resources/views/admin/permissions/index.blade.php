@extends('layouts.app')

@section('title', 'Manajemen Izin')
@section('page-title', 'Manajemen Izin')

@section('content')
    <div class="flex-1 flex flex-col overflow-hidden">
        <div class="flex-1 overflow-auto">
            <div class="p-8">

                {{-- Flash messages --}}
                @if (session('success'))
                    <div class="mb-4 px-4 py-3 bg-green-100 text-green-800 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 px-4 py-3 bg-red-100 text-red-800 rounded-lg text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    {{-- Form Tambah Izin --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h2 class="text-base font-semibold text-gray-900 mb-4">Tambah Izin Baru</h2>
                            <form action="{{ route('permissions.store') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Izin
                                    </label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                                        placeholder="Contoh: lansia.export"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                                    @error('name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-gray-400 text-xs mt-1">Gunakan format: <code>resource.aksi</code></p>
                                </div>
                                <button type="submit"
                                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                    <i class="fas fa-plus mr-1"></i> Tambah Izin
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Daftar Izin --}}
                    <div class="lg:col-span-2">
                        @forelse ($permissions as $resource => $group)
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-4">
                                <div class="px-5 py-3 bg-gray-50 border-b border-gray-200 flex items-center gap-2">
                                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ $resource }}</span>
                                    <span class="text-xs text-gray-400">({{ $group->count() }} izin)</span>
                                </div>
                                <ul class="divide-y divide-gray-100">
                                    @foreach ($group as $permission)
                                        <li class="flex items-center justify-between px-5 py-3">
                                            <span class="text-sm font-medium text-gray-800">{{ $permission->name }}</span>
                                            <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus izin {{ $permission->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-700 text-xs px-2 py-1 rounded hover:bg-red-50 transition-colors">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @empty
                            <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
                                <i class="fas fa-lock text-4xl mb-3 block opacity-30"></i>
                                <p>Belum ada izin. Tambahkan izin pertama.</p>
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
