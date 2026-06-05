<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SuperAdminController extends Controller
{
    // Tampilkan semua superadmin
    public function index()
    {
        $users = User::where('role', 'superadmin')->paginate(10);
        return view('superadmin.index', compact('users'));
    }

    // Simpan superadmin baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|max:255',
            'email'     => 'required|email|unique:users|max:255',
            'password'  => 'required|string|min:6',
            'sex'       => 'required|in:male,female',
            'telephone' => 'required|numeric',
            'position'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'superadmin',
            'sex'       => $request->sex,
            'phone'     => $request->telephone,
            'position'  => $request->position,
        ]);

        return redirect()->route('superadmin.index')->with('success', 'Superadmin berhasil ditambahkan');
    }

    // Update superadmin
    public function update(Request $request, $id)
    {
        $superadmin = User::where('role', 'superadmin')->findOrFail($id);

        $superadmin->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'sex'       => $request->sex,
            'phone'     => $request->telephone,
            'position'  => $request->position,
        ]);

        if ($request->filled('password')) {
            $superadmin->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('superadmin.index')->with('success', 'Superadmin berhasil diperbarui');
    }

    // Hapus superadmin
    public function destroy($id)
    {
        $superadmin = User::where('role', 'superadmin')->findOrFail($id);
        $superadmin->delete();

        return redirect()->route('superadmin.index')->with('success', 'Superadmin berhasil dihapus');
    }

    public function bulkDelete(Request $request)
{
    $ids = $request->ids;

    if (!empty($ids)) {
        User::whereIn('id', $ids)->delete();
        return redirect()->route('superadmin.index')->with('success', 'Data berhasil dihapus.');
    }

    return redirect()->route('superadmin.index')->with('error', 'Tidak ada data yang dipilih.');
}

}
