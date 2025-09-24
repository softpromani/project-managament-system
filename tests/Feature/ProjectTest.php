<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles and permissions
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    }

    public function test_admin_can_create_project(): void
    {
        $admin = User::factory()->admin()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/projects', [
                'title' => 'Test Project',
                'description' => 'Test project description',
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id', 'title', 'description', 'start_date', 'end_date'
                ]
            ]);

        $this->assertDatabaseHas('projects', [
            'title' => 'Test Project',
            'created_by' => $admin->id,
        ]);
    }

    public function test_user_cannot_create_project(): void
    {
        $user = User::factory()->user()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/projects', [
                'title' => 'Test Project',
                'description' => 'Test project description',
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
            ]);

        $response->assertStatus(403);
    }

    public function test_can_list_projects(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        Project::factory()->count(3)->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/projects');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'data' => [
                        '*' => ['id', 'title', 'description', 'start_date', 'end_date']
                    ]
                ]
            ]);
    }
}
