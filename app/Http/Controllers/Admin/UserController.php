<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('role')->orderBy('name')->paginate(15)->withQueryString();
        $guruList = User::where('role', 'guru')->get();
        return view('admin.users.index', compact('users', 'guruList'));
    }

    public function create()
    {
        $guruList = User::where('role', 'guru')->get();
        return view('admin.users.create', compact('guruList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:admin,guru,siswa'],
            'kelas' => ['nullable', 'string', 'max:50'],
            'guru_id' => ['nullable', 'exists:users,id'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'kelas' => $request->kelas,
            'guru_id' => $request->guru_id,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function show(User $user)
    {
        if ($user->role !== 'siswa') {
            return back()->with('error', 'Detail lengkap hanya tersedia untuk siswa.');
        }

        $trackings = $user->dailyTrackings()->orderByDesc('tracking_date')->get();
        $pretest = $user->pretest;
        $posttest = $user->posttest;

        return view('admin.users.show', compact('user', 'trackings', 'pretest', 'posttest'));
    }

    public function edit(User $user)
    {
        $guruList = User::where('role', 'guru')->get();
        return view('admin.users.edit', compact('user', 'guruList'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,guru,siswa'],
            'kelas' => ['nullable', 'string', 'max:50'],
            'guru_id' => ['nullable', 'exists:users,id'],
        ]);

        $data = $request->only(['name', 'email', 'role', 'kelas', 'guru_id']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }
}
