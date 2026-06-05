<?php

// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function paid()
    {
        $today = Carbon::today();

        $users = User::with(['memberships.paket'])
            ->where('role', 'student')
            ->whereHas('memberships', function ($q) use ($today) {
                $q->whereDate('expired_at', '>=', $today);
            })
            ->paginate(10);

        return view('users.paid', compact('users'));
    }

    public function unpaid()
    {
        $today = Carbon::today();

        $users = User::with('memberships')
            ->where('role', 'student')
            ->where(function ($q) use ($today) {
                // User tidak memiliki membership aktif
                $q->whereDoesntHave('memberships', function ($q2) use ($today) {
                    $q2->whereDate('expired_at', '>=', $today);
                });
            })
            ->paginate(10);

        return view('users.unpaid', compact('users'));
    }

    public function superadmins()
    {
        $superadmins = User::where('role', 'superadmin')->paginate(10);
        return view('users.superadmins', compact('superadmins'));
    }

    // ✅ untuk route('users.edit', $id)
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // ✅ untuk update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
        ]);

        $user->update($request->only('name', 'position', 'company'));

        return redirect()->route('users.paid')->with('success', 'User updated successfully');
    }

    public function store(Request $request)
    {
        // Debug sementara
        // dd($request->all());

        // Validasi semua field
        $request->validate([
            'name'       => 'nullable|string|max:255',
            'email'      => 'nullable|email|unique:users,email',
            'password'   => 'nullable|string|min:6',
            'sex'        => 'nullable|in:male,female',
            'telephone'  => 'nullable|string',
            // 'type_user'  => 'nullable|in:superadmin,admin,student',
            'position'   => 'nullable|string|max:255',
        ]);

        // Simpan user
        User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'sex'        => $request->sex,
            'phone'      => $request->telephone,  // pastikan field di DB 'phone'
            'role'       => $request->type_user,
            'position'   => $request->position,
            'role'       => 'superadmin', // tetap superadmin
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id); // cari user
        $user->delete(); // hapus user

        // kembalikan response
        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
}
