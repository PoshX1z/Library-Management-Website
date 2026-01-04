<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class StaffController extends Controller
{
    public function index()
    {
        $staffs = Staff::orderBy('role', 'asc')->get();
        return view('staffs.index', compact('staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:staffs,email',
            'password' => 'required|min:6',
            'role' => 'required',
            'phone' => 'required'
        ]);

        Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'created_at' => Carbon::now()
        ]);

        return redirect()->back()->with('success', 'เพิ่มบุคลากรเรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:staffs,email,' . $id,
            'role' => 'required',
            'phone' => 'required'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $staff->update($data);

        return redirect()->back()->with('success', 'แก้ไขข้อมูลบุคลากรเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        Staff::destroy($id);
        return redirect()->back()->with('success', 'ลบบุคลากรเรียบร้อยแล้ว');
    }
}