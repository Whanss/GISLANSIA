@extends('layouts.app')

@section('title', 'Peran & Izin')
@section('page-title', 'Peran & Izin')

@section('content')
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Content --}}
        <div class="flex-1 overflow-auto">
            <div class="p-8">
                {{-- Search & Filter --}}
                <div class="mb-6 flex items-center gap-4">
                    <input type="text" placeholder="Cari peran..." id="searchInput"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('roles.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Peran
                    </a>
                </div>

                {{-- Table --}}
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Nama Peran</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Izin</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Dibuat</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($roles as $role)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ ucfirst($role->name) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            @if ($role->permissions->count() > 0)
                                                @foreach ($role->permissions->take(3) as $permission)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $permission->name }}
                                                    </span>
                                                @endforeach
                                                @if ($role->permissions->count() > 3)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        +{{ $role->permissions->count() - 3 }} lainnya
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-gray-500 text-sm">Tidak ada izin</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600">{{ $role->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('roles.edit', $role->id) }}"
                                                class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition-colors text-sm font-medium">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Yakin ingin menghapus peran ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center px-3 py-1 bg-red-50 text-red-600 rounded hover:bg-red-100 transition-colors text-sm font-medium">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center">
                                        <div class="text-gray-500">
                                            <i class="fas fa-inbox text-4xl mb-3 block opacity-40"></i>
                                            <p class="font-medium">Tidak ada peran ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Info --}}
                @if ($roles->count() > 0)
                    <div class="mt-4 text-sm text-gray-600">
                        Menampilkan <strong>{{ $roles->count() }}</strong> peran
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>
@endsection
