<?php

namespace Tests\Feature\Http\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class IsAuthorizedTest extends TestCase
{
    /* Middleware:
        1. Create middleware -- php artisan make:middleware IsAdmin
        2. Register in Kernel.php at $routeMiddleware
        3. Apply to web.php: Route::group(['middleware' => 'isAdmin'], function () {}         
    */

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['name'=>'Admin','description'=>'Administrator role']);
        $managerRole = Role::create(['name'=>'Manager','description'=>'Manager role']);
        $employeeRole = Role::create(['name'=>'Employee','description'=>'Employee role']);

        $adminUser = User::create(['name'=>'Admin Only', 'email'=>'shyuadmin@yahoo.com', 'password'=>Hash::make('12345678')]);
        $managerUser = User::create(['name'=>'Manager Only', 'email'=>'shyumanager@yahoo.com', 'password'=>Hash::make('12345678')]);
        $employeeUser = User::create(['name'=>'Employee Only', 'email'=>'shyuemployee@yahoo.com', 'password'=>Hash::make('12345678')]);
        
        $adminUser->roles()->attach($adminRole);
        $managerUser->roles()->attach($managerRole);
        $employeeUser->roles()->attach($employeeRole);
    }

    public function test_is_authorized_for_admin_success()
    {
        // Log in as admin
        $response = $this->get('/api/login');
        $response = $this->post('/api/login', ['email'=>'shyuadmin@yahoo.com', 'password'=>'12345678']);
        $response->assertStatus(200);
        $status = $response->json()['status'];
        $this->assertEquals(2, $status);

        // Log in as admin to access /employee/list
        $response = $this->get('/employees/list');
        $response->assertStatus(200);
        $response->assertSee('Chinamax Employees');
    }

    public function test_is_authorized_for_manager_success()
    {
        // Log in as admin
        $response = $this->get('/api/login');
        $response = $this->post('/api/login', ['email'=>'shyumanager@yahoo.com', 'password'=>'12345678']);
        $response->assertStatus(200);
        $status = $response->json()['status'];
        $this->assertEquals(2, $status);

        // Log in as admin to access /employee/list
        $response = $this->get('/employees/list');
        $response->assertStatus(200);
        $response->assertSee('Chinamax Employees');
    }

    public function test_is_authorized_for_employee_failed()
    {
        // Log in as admin
        $response = $this->get('/api/login');
        $response = $this->post('/api/login', ['email'=>'shyuemployee@yahoo.com', 'password'=>'12345678']);
        $response->assertStatus(200);
        $status = $response->json()['status'];
        $this->assertEquals(3, $status);

        $message = $response->json()['msg'];
        $this->assertEquals('This user is not allowed to access employee information', $message);
    }

    public function test_is_authorized_failed()
    {
        // Do not log in any user and access /employee/list
        $response = $this->get('/employees/list');
        $response->assertStatus(302);
    }
}
