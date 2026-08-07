<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;
use App\Models\Department;

class DirectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $management = Department::where('name', 'management')->first();
        $directorRole = Role::where('name', 'director')->firstOrFail();
      $user =  User::create([
            'department_id' => $management->id,
            'first_name' => 'Director',
            'last_name' => 'Account',
            'email' => 'director@restaurant.test',
            'password' => Hash::make('password'),
            'must_change_password' => false,
            'is_active' => true,
        ]);

        $user->roles()->attach($directorRole->id);
    }
}
