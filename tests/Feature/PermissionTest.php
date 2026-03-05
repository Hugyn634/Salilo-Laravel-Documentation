<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_be_assigned_a_role_and_permissions_are_checked()
    {
        // create a user
        $user = User::factory()->create();

        // create a role and permission via spatie's models
        $role = Role::create(['name' => 'writer']);
        $permission = Permission::create(['name' => 'edit articles']);

        // assign permission to role and role to user
        $role->givePermissionTo($permission);
        $user->assignRole('writer');

        // assert user has role and permission
        $this->assertTrue($user->hasRole('writer'));
        $this->assertTrue($user->can('edit articles'));

        // test negative case
        $this->assertFalse($user->can('delete articles'));
    }
}
