<?php

namespace Tests\Feature\Http\Controllers\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserController;

class RetrieveUsersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create([
            'name'=>'Admin',
            'description'=>'Administrator role'
        ]);
        Role::create([
            'name'=>'Manager',
            'description'=>'Manager role'
        ]);
        Role::create([
            'name'=>'Employee',
            'description'=>'Employee role'
        ]);
        $userAdmin = User::create([
            'name'=>'Admin Shyu',
            'email'=>'shyuadmin@yahoo.com',
            'password'=>Hash::make('12345678')
        ]);
        $adminRole = DB::table('roles')->select('id')->where('name', 'Admin')->first();
        $managerRole = DB::table('roles')->select('id')->where('name', 'Manager')->first();
        $employeeRole = DB::table('roles')->select('id')->where('name', 'Employee')->first();
        
        $userAdmin->roles()->attach($adminRole);
        $userAdmin->roles()->attach($managerRole);
        $userAdmin->roles()->attach($employeeRole);

        $userManager = User::create([
            'name'=>'Manager Shyu',
            'email'=>'shyumanager@yahoo.com',
            'password'=>Hash::make('12345678')
        ]);
        $userManager->roles()->attach($managerRole);
        $userManager->roles()->attach($employeeRole);

        $userEmployee = User::create([
            'name'=>'Employee Shyu',
            'email'=>'shyuemployee@yahoo.com',
            'password'=>Hash::make('12345678')
        ]);
        $userEmployee->roles()->attach($employeeRole);
    }

    public function test_retrieve_user_by_id()
    {
        $response = $this->get('/api/user/1');
        $response->assertStatus(200);
        
        $this->assertEquals(1, $response->getData()->id);
        $this->assertEquals('Admin Shyu', $response->getData()->name);
        $this->assertEquals('shyuadmin@yahoo.com', $response->getData()->email);
        $this->assertTrue(Hash::check('12345678', $response->getData()->password));
    }

    public function test_list_users_with_roles()
    {
        $response = $this->post('/api/login', ['email'=>'shyuadmin@yahoo.com', 'password'=>'12345678']);
        $response->assertStatus(200);
        $response = $this->get('api/usersWithRoles');
        $response->assertStatus(200);
        
        $this->assertEquals(1, $response->getData()[0]->id);
        $this->assertEquals('Admin Shyu', $response->getData()[0]->name);
        $this->assertEquals('shyuadmin@yahoo.com', $response->getData()[0]->email);
        $this->assertTrue(Hash::check('12345678', $response->getData()[0]->password));
        $this->assertEquals(1, $response->getData()[0]->admin);
        $this->assertEquals(1, $response->getData()[0]->manager);
        $this->assertEquals(1, $response->getData()[0]->employee);

        $this->assertEquals(2, $response->getData()[1]->id);
        $this->assertEquals('Manager Shyu', $response->getData()[1]->name);
        $this->assertEquals('shyumanager@yahoo.com', $response->getData()[1]->email);
        $this->assertTrue(Hash::check('12345678', $response->getData()[1]->password));
        $this->assertEquals(0, $response->getData()[1]->admin);
        $this->assertEquals(1, $response->getData()[1]->manager);
        $this->assertEquals(1, $response->getData()[1]->employee);

        $this->assertEquals(3, $response->getData()[2]->id);
        $this->assertEquals('Employee Shyu', $response->getData()[2]->name);
        $this->assertEquals('shyuemployee@yahoo.com', $response->getData()[2]->email);
        $this->assertTrue(Hash::check('12345678', $response->getData()[2]->password));
        $this->assertEquals(0, $response->getData()[2]->admin);
        $this->assertEquals(0, $response->getData()[2]->manager);
        $this->assertEquals(1, $response->getData()[2]->employee);
    }
}
