<?php

namespace Tests\Feature\Http\Controllers\Employee;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Role;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class InvokeTest extends TestCase
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
        $user = User::create([
            'name'=>'Admin Shyu',
            'email'=>'shyuadmin@yahoo.com',
            'password'=>Hash::make('12345678')
        ]);
        $adminRole = DB::table('roles')->select('id')->where('name', 'Admin')->first();
        $user->roles()->attach($adminRole);

        $employee = Employee::create([
            'first_name' => 'Jacky',
            'middle_name' => 'M',
            'last_name' => 'Shyu',
            'email' => 'shyujacky@yahoo.com',
            'phone' => '214-680-8281',
            'birthday' => '01/01/2000',
            'ssn' => '123-45-6789',
            'gender' => 'M',
            'position' => 'Manager',
            'salary' => '60000.00',
            'address' => '123 Laurel Springs St.',
            'address2' => 'Suite 100',
            'city' => 'Grapevine',
            'state' => 'TX',
            'zip' => '75115',
            'img' => 'img.jpg',
            'start_date' => '01/01/2022',
            'end_date' => '01/01/2024',
        ]);
    }

    public function test_invoke()
    {
        // Log in as administrator to access user list
        $response = $this->post('api/login', ['email'=>'shyuadmin@yahoo.com', 'password'=>'12345678']);
        $response->assertStatus(200);

        $response = $this->get('/employees/list');
        $response->assertStatus(200);
        // Call __invoke() function in EmployeeController.php, then view('employees.index')
        $response->assertSee('Chinamax Employees');
    }

    public function test_index()
    {
        // Log in as administrator to access user list
        $response = $this->post('api/login', ['email'=>'shyuadmin@yahoo.com', 'password'=>'12345678']);
        $response->assertStatus(200);

        $response = $this->get('api/employees');
        $response->assertStatus(200);

        $this->assertEquals(1, $response->getData()[0]->id);
        $this->assertEquals('Jacky', $response->getData()[0]->first_name);
        $this->assertEquals('M', $response->getData()[0]->middle_name);
        $this->assertEquals('Shyu', $response->getData()[0]->last_name);
        $this->assertEquals('shyujacky@yahoo.com', $response->getData()[0]->email);
        $this->assertEquals('214-680-8281', $response->getData()[0]->phone);
        $this->assertEquals('01/01/2000', $response->getData()[0]->birthday);
        $this->assertEquals('123-45-6789', $response->getData()[0]->ssn);
        $this->assertEquals('M', $response->getData()[0]->gender);
        $this->assertEquals('Manager', $response->getData()[0]->position);
        $this->assertEquals('60000', $response->getData()[0]->salary);
        $this->assertEquals('123 Laurel Springs St.', $response->getData()[0]->address);
        $this->assertEquals('Suite 100', $response->getData()[0]->address2);
        $this->assertEquals('Grapevine', $response->getData()[0]->city);
        $this->assertEquals('TX', $response->getData()[0]->state);
        $this->assertEquals('75115', $response->getData()[0]->zip);
        $this->assertEquals('img.jpg', $response->getData()[0]->img);
        $this->assertEquals('01/01/2022', $response->getData()[0]->start_date);
        $this->assertEquals('01/01/2024', $response->getData()[0]->end_date);
    }
}
