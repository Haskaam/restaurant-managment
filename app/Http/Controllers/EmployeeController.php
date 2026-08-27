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

        $currentUser = $request->user();

        $role = Role::findOrFail($request->role_id);

        if ($currentUser->hasRole('manager') && in_array($role->name, ['director', 'manager'])) {
            abort(403);
        }

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



    public function index(Request $request)
    {
        $query = User::with(['department', 'roles']);

        if ($request->filled('search')) {
            $query->where(function ($query) use ($request) {
                $query->where(
                    'first_name',
                    'like',
                    '%' . $request->search . '%'
                )
                ->orWhere(
                    'last_name',
                    'like',
                    '%' . $request->search . '%'
                )
                ->orWhere(
                    'email',
                    'like',
                    '%' . $request->search .'%'
                );
            });
        }

            $allowedSorts = [
                'first_name',
                'last_name',
                'email',
                'is_active',
            ];

            $sort = in_array($request->sort, $allowedSorts)
                ? $request->sort
                : 'last_name';

            $direction = $request->direction === 'desc'
                ? 'desc'
                : 'asc';

            $employees = $query->orderBy($sort, $direction)->get();

            return view('employees.index', compact(
                'employees',
                'sort',
                'direction'
            ));
    }



    public function edit(User $user, Request $request)
    {
        $currentUser = $request->user();

        if($currentUser->hasRole('manager') && ($user->hasRole('director') || $user->hasRole('manager'))) {
            abort(403);
        }


        $departments = Department::all();
        $roles = Role::all();

        $user->load('roles');
        return view('employees.edit', compact('user', 'departments', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'department_id' => ['required', 'exists:departments,id'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $currentUser = $request->user();

        $role = Role::findOrFail($data['role_id']);

        if(
            $currentUser->hasRole('manager')
            && in_array($role->name, ['director', 'manager'])
        ) {
            abort(403);
        }

        $user->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'department_id' => $data['department_id'],
        ]);

        $user->roles()->sync([$role->id]);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Dane pracownika zostały zaktualizowane.');
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
