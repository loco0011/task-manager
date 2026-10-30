<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private function taskData($status = 'Pending')
    {
        return [
            'title' => 'Write report',
            'description' => 'Monthly report',
            'due_date' => '2026-12-01',
            'status' => $status,
        ];
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/tasks')->assertRedirect(route('login'));
    }

    public function test_user_can_register_and_login(): void
    {
        $this->post('/register', [
            'name' => 'Ravi',
            'email' => 'ravi@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ])->assertRedirect('/tasks');

        $this->post('/logout')->assertRedirect(route('login'));

        $this->post('/login', [
            'email' => 'ravi@example.com',
            'password' => 'secret123',
        ])->assertRedirect('/tasks');
        $this->assertAuthenticated();
    }

    public function test_any_id_and_password_can_login(): void
    {
        $this->post('/login', [
            'email' => 'anyone',
            'password' => 'anything',
        ])->assertRedirect('/tasks');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'anyone']);
        $this->post('/logout');

        $this->post('/login', [
            'email' => 'anyone',
            'password' => 'something-else',
        ])->assertRedirect('/tasks');
        $this->assertDatabaseCount('users', 1);
    }

    public function test_login_requires_id_and_password(): void
    {
        $this->post('/login', [])->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    public function test_user_can_create_edit_and_delete_task(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/tasks', $this->taskData())->assertRedirect('/tasks');
        $task = $user->tasks()->first();
        $this->assertSame('Write report', $task->title);

        $this->get("/tasks/{$task->id}/edit")->assertOk()->assertSee('2026-12-01');
        $this->put("/tasks/{$task->id}", $this->taskData('Completed'))->assertRedirect('/tasks');
        $this->assertSame('Completed', $task->fresh()->status);

        $this->delete("/tasks/{$task->id}")->assertRedirect('/tasks');
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_invalid_task_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/tasks', [
            'title' => '',
            'due_date' => 'not-a-date',
            'status' => 'Unknown',
        ])->assertSessionHasErrors(['title', 'due_date', 'status']);
    }

    public function test_tasks_can_be_filtered_by_status(): void
    {
        $user = User::factory()->create();
        $user->tasks()->create(array_merge($this->taskData(), ['title' => 'First task']));
        $user->tasks()->create(array_merge($this->taskData('In Progress'), ['title' => 'Second task']));

        $this->actingAs($user)->get('/tasks?status=In Progress')
            ->assertSee('Second task')
            ->assertDontSee('First task');
    }

    public function test_user_cannot_touch_another_users_task(): void
    {
        $owner = User::factory()->create();
        $task = $owner->tasks()->create($this->taskData());
        $other = User::factory()->create();

        $this->actingAs($other)->get('/tasks')->assertDontSee('Write report');
        $this->get("/tasks/{$task->id}/edit")->assertNotFound();
        $this->put("/tasks/{$task->id}", $this->taskData('Completed'))->assertNotFound();
        $this->delete("/tasks/{$task->id}")->assertNotFound();
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'Pending']);
    }
}
