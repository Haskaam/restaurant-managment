<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Department;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_dashboard_access(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect(route('login'));
    }

    public function test_logged_in_user_access(): void
    {
        $department = Department::create([
            'name' => 'service',
        ]);

        $user = User::factory()->create([
            'department_id' => $department->id,
            'must_change_password' => false,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertSuccessful();
    }
}
