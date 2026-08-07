<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'department_id' => 'required|exists:departments,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'department_id' => $request->department_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'must_change_password' => true,
            'is_active' => true,
        ]);

        $user->roles()->attach($request->role_id);

        return redirect()->back()->with('success', 'Pracownik został utworzony.');
    }

    public function create()
    {
        $departments = Department::all();
        $roles = Role::all();

        return view('employees.create', compact('departments', 'roles'));
    }

    public function index()
    {
        $employees = User::with(['department', 'roles'])->get();

        return view('employees.index', compact('employees'));
    }

    public function edit(User $user)
    {
        $departments = Department::all();
        $roles = Role::all();

        $user->load('roles');
        return view('employees.edit', compact('user', 'departments', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'department' => 'required|exists:departments,id',
            'role' => 'required|exists:roles,id'
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'department_id' => $request->department,
        ]);

        $user->roles()->sync($request->role);

        return redirect()->route('employees.index')->with('success', 'Dane pracownika zostały zaktualizowane.');
    }

    public function terminate(User $user)
    {
        if($user->hasRole('director')) {
            return redirect()->route('employees.index')->withErrors([
                'employee' => 'Nie można zwolnić użytkownika z rolą Director'
            ]);
        }

        $user->update([
            'is_active' => false,
            'employment_ended_at' => now(),
        ]);

        return redirect()->route('employees.index')->with('success', 'Pracownik został zwolniony.');
    }
}
