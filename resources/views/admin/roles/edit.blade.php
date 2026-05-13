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
            <div class="p-8 max-w-4xl">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- Kiri: Form edit permission --}}
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

                        {{-- Izin --}}Gambar 18. Kode Tambah Role dan Permission
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-900 mb-3">
                                Izin <span class="text-red-500">*</span>
                            </label>
                            @if($isOwnRole)
                                <div class="mb-3 px-3 py-2 bg-yellow-50 border border-yellow-200 rounded-lg text-xs text-yellow-700">
                                    <i class="fas fa-shield-alt mr-1"></i>
                                    Ini adalah peran Anda sendiri. Permission <strong>role.*</strong> dan <strong>permission.*</strong> yang Anda miliki tidak dapat dicabut untuk mencegah kehilangan akses.
                                </div>
                            @endif
                            <div class="space-y-3">
                                @php
                                    $rolePermissions = $role->permissions->pluck('id')->toArray();
                                @endphp
                                @forelse($permissions as $permission)
                                    @php
                                        $isLocked = $isOwnRole
                                            && $currentUserRolePermissions->contains($permission->id)
                                            && (str_starts_with($permission->name, 'role.') || str_starts_with($permission->name, 'permission.'));
                                    @endphp
                                    <div class="flex items-center {{ $isLocked ? 'opacity-50' : '' }}">
                                        <input type="checkbox"
                                            id="permission_{{ $permission->id }}"
                                            name="permissions[]"
                                            value="{{ $permission->id }}"
                                            @if(in_array($permission->id, $rolePermissions)) checked @endif
                                            @if($isLocked) disabled @endif
                                            class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 {{ $isLocked ? 'cursor-not-allowed' : '' }}">
                                        <label for="permission_{{ $permission->id }}" class="ml-3 text-sm text-gray-700 {{ $isLocked ? 'cursor-not-allowed select-none' : '' }}">
                                            <span class="font-medium">{{ $permission->name }}</span>
                                            @if($isLocked)
                                                <span class="ml-2 text-xs text-gray-400"><i class="fas fa-lock"></i></span>
                                            @endif
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
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('roles.index') }}"
                                class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>

                    {{-- Kanan: Assign user ke role ini --}}
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Pengguna dengan Peran Ini</h2>

                        {{-- User yang sudah punya role ini --}}
                        @php $roleUsers = $users->filter(fn($u) => $u->roles->contains('id', $role->id)); @endphp
                        @if($roleUsers->count() > 0)
                            <ul class="divide-y divide-gray-100 mb-4">
                                @foreach($roleUsers as $u)
                                    <li class="flex items-center justify-between py-2.5">
                                        <div class="flex items-center gap-2">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($u->name) }}&background=4f46e5&color=fff&size=28"
                                                class="w-7 h-7 rounded-full">
                                            <div>
                                                <p class="text-sm font-medium text-gray-800">{{ $u->name }}</p>
                                                <p class="text-xs text-gray-400">{{ $u->email }}</p>
                                            </div>
                                        </div>
                                        @if(auth()->id() !== $u->id)
                                            <form action="{{ route('roles.assign-user', $u->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="role" value="">
                                                <button type="submit"
                                                    onclick="return confirm('Hapus {{ $u->name }} dari peran ini?')"
                                                    class="text-xs text-red-500 hover:text-red-700 px-2 py-1 rounded hover:bg-red-50 transition-colors">
                                                    <i class="fas fa-times mr-1"></i>Hapus
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400"><i class="fas fa-lock"></i></span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-400 mb-4">Belum ada pengguna dengan peran ini.</p>
                        @endif

                        {{-- Tambah user ke role ini --}}
                        @php $availableUsers = $users->filter(fn($u) => !$u->roles->contains('id', $role->id)); @endphp
                        @if($availableUsers->count() > 0)
                            <div class="border-t border-gray-100 pt-4">
                                <p class="text-xs font-medium text-gray-500 mb-2">Tambah pengguna ke peran ini:</p>
                                <form action="{{ route('roles.assign-user', '__user__') }}" method="POST"
                                    id="assignUserForm" class="flex gap-2">
                                    @csrf
                                    <input type="hidden" name="role" value="{{ $role->name }}">
                                    <select name="_user_id" id="userSelect"
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- Pilih pengguna --</option>
                                        @foreach($availableUsers as $u)
                                            @php $existingRole = $u->roles->first(); @endphp
                                            <option value="{{ $u->id }}" {{ $existingRole ? 'disabled' : '' }}>
                                                {{ $u->name }}{{ $existingRole ? ' (sudah: ' . ucfirst($existingRole->name) . ')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-colors">
                                        Tambah
                                    </button>
                                </form>
                                <p class="text-xs text-gray-400 mt-1">Pengguna yang sudah memiliki peran lain tidak dapat dipilih.</p>
                            </div>
                        @endif
                    </div>

                </div>{{-- end grid --}}
            </div>
        </div>
    </div>

    <script>
        // Update form action dengan user id yang dipilih
        document.getElementById('assignUserForm')?.addEventListener('submit', function(e) {
            const userId = document.getElementById('userSelect').value;
            if (!userId) { e.preventDefault(); return; }
            this.action = this.action.replace('__user__', userId);
        });
    </script>
@endsection
