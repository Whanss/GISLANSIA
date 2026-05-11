<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user.view')->only(['index']);
        $this->middleware('permission:user.create')->only(['create', 'store']);
        $this->middleware('permission:user.edit')->only(['edit', 'update']);
        $this->middleware('permission:user.delete')->only(['destroy']);
    }

    public function index()
    {
        $users = User::with("roles")->paginate(10);
        return view("admin.users.index", compact("users"));
    }

    public function create()
    {
        $roles = Role::all();
        return view("admin.users.create", compact("roles"));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name'
        ], [
            'password.confirmed' => 'Kata Sandi dan Konfirmasi Kata Sandi tidak cocok.',
            'password.min' => 'Kata Sandi harus minimal 8 karakter.',
            'password.required' => 'Kata Sandi wajib diisi.',
            'name.required' => 'Nama Lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus format email yang valid.',
            'email.unique' => 'Email sudah terdaftar.',

        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dibuat');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view("admin.users.edit", compact("user", "roles"));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'password.confirmed' => 'Kata Sandi dan Konfirmasi Kata Sandi tidak cocok.',
            'password.min'       => 'Kata Sandi harus minimal 8 karakter.',
            'name.required'      => 'Nama Lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Email harus format email yang valid.',
            'email.unique'       => 'Email sudah terdaftar.',
        ]);

        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        // Prevent deleting the authenticated user
        if (auth()->user()->id === $user->id) {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus akun Anda sendiri');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus');
    }
}

