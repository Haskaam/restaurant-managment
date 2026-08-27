<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $department = Department::where('name', 'management')
            ->firstOrFail();

        $demo = User::updateOrCreate(
            [
                'email' => 'demo@restaurant.test',
            ],
            [
                'department_id' => $department->id,
                'first_name' => 'Demo',
                'last_name' => 'Account',
                'password' => Hash::make('password'),
                'must_change_password' => false,
                'is_active' => true,
                'employment_ended_at' => null,
            ]
        );

        $roles = Role::whereIn('name', [
            'director',
            'manager',
            'waiter',
            'cook',
            'kitchen_assistant',
        ])->pluck('id');

        $demo->roles()->sync($roles);
    }
}
