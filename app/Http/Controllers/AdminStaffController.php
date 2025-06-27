<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class AdminStaffController extends Controller
{
    // Daftar divisi staff tetap
    private $divisiList = ['VERA', 'BANK', 'MSKI', 'PD', 'UMUM'];

    public function index()
    {
        $staffs = User::where('role', 'staff')->get();
        return view('admin.staffs.index', compact('staffs'));
    }

    public function create()
    {
        $divisiList = $this->divisiList;

        // Ambil semua divisi yang sudah dipakai oleh staff
        $usedDivisi = User::where('role', 'staff')->pluck('divisi')->toArray();

        return view('admin.staffs.create', compact('divisiList', 'usedDivisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nip' => 'required|string',
            'divisi' => [
                'required',
                Rule::in($this->divisiList),
                Rule::unique('users')->where(fn($q) => $q->where('role', 'staff'))
            ],
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'divisi' => $request->divisi,
            'role' => 'staff',
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.staffs.index')->with('success', 'Staff berhasil ditambahkan');
    }

    public function edit(User $staff)
    {
        if ($staff->role !== 'staff') {
            abort(404);
        }

        $divisiList = $this->divisiList;
        $usedDivisi = User::where('role', 'staff')
            ->where('id', '!=', $staff->id)
            ->pluck('divisi')
            ->toArray();

        return view('admin.staffs.edit', compact('staff', 'divisiList', 'usedDivisi'));
    }

    public function update(Request $request, User $staff)
    {
        if ($staff->role !== 'staff') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($staff->id),
            ],
            'nip' => 'required|string',
            'divisi' => [
                'required',
                Rule::in($this->divisiList),
                Rule::unique('users')
                    ->ignore($staff->id)
                    ->where(fn($q) => $q->where('role', 'staff'))
            ],
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'divisi' => $request->divisi,
            'role' => 'staff',
            'password' => $request->password
                ? Hash::make($request->password)
                : $staff->password,
        ]);

        return redirect()->route('admin.staffs.index')->with('success', 'Staff berhasil diperbarui');
    }

    public function destroy(User $staff)
    {
        if ($staff->role !== 'staff') {
            abort(404);
        }

        $staff->delete();
        return redirect()->route('admin.staffs.index')->with('success', 'Staff berhasil dihapus');
    }
}
