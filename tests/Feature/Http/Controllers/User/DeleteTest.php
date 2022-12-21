<?php

namespace Tests\Feature\Feature\Http\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create([
            'name'=>'Admin',
            'description'=>'Administrator role'
        ]);
        $user = User::create([
            'name'=>'Admin Shyu',
            'email'=>'shyuadmin@yahoo.com',
            'password'=>Hash::make('12345678')
        ]);
        $adminRole = DB::table('roles')->select('id')->where('name', 'Admin')->first();
        $user->roles()->attach($adminRole);
    }

    public function test_delete_user_success()
    {
        // Log in as administrator to access user list
        $response = $this->post('/api/login', ['email'=>'shyuadmin@yahoo.com', 'password'=>'12345678']);
        $response->assertStatus(200);

        $user = DB::table('users')->where('id', 1)->first();
        $this->assertEquals('Admin Shyu', $user->name);

        $response = $this->call('POST', '/api/users/delete/1');
        $response->assertStatus(200);
        $message = $response->json()['msg'];
        $this->assertEquals('The roles of Admin Shyu have been deleted successfully.', $message);

        $status = $response->json()['status'];
        $this->assertEquals(1, $status);

        $user = DB::table('users')->where('id', 1)->first();
        $this->assertEquals(null, $user);
    }

    public function test_delete_user_not_success()
    {
        // Log in as administrator to access user list
        $response = $this->post('/api/login', ['email'=>'shyuadmin@yahoo.com', 'password'=>'12345678']);
        $response->assertStatus(200);

        $response = $this->call('POST', '/api/users/delete/2');
        //$response->assertStatus(200);
        $message = $response->json()['msg'];
        $this->assertEquals('Deletion failed.', $message);

        $status = $response->json()['status'];
        $this->assertEquals(0, $status);
    }
}
