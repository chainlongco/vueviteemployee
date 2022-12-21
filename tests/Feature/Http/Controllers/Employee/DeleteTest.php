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
use App\Http\Controllers\EmployeeController;
use Exception;

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

    public function test_delete_employee_success()
    {
        // Log in as administrator to access user list
        $response = $this->post('/api/login', ['email'=>'shyuadmin@yahoo.com', 'password'=>'12345678']);
        $response->assertStatus(200);

        $employee = DB::table('employees')->where('id', 1)->first();
        $this->assertEquals('Jacky', $employee->first_name);
        $this->assertEquals('Shyu', $employee->last_name);
        $fullName = $employee->first_name .' ' .$employee->last_name;

        $response = $this->call('POST', '/api/employees/delete/1');
        $response->assertStatus(200);
        $status = $response->json()['status'];
        $this->assertEquals('2', $status);
        $message = $response->json()['msg'];
        $expectedMessage = 'Employee: ' .$fullName .' has been deleted successfully!';
        $this->assertEquals($expectedMessage, $message);

        $employee = DB::table('employees')->where('id', 1)->first();
        $this->assertEquals(null, $employee);
    }

    public function test_delete_employee_not_success_employee_not_found()
    {
        // Log in as administrator to access user list
        $response = $this->post('/api/login', ['email'=>'shyuadmin@yahoo.com', 'password'=>'12345678']);
        $response->assertStatus(200);

        $response = $this->call('POST', '/api/employees/delete/2');
        //$response->assertStatus(200);
        $status = $response->json()['status'];
        $this->assertEquals(0, $status);
        $message = $response->json()['msg'];
        $this->assertEquals('Sorry, employee not found', $message);
    }

    public function test_delete_employee_not_success_cannot_be_deleted()
    {
        $employeeControllerTester = new EmployeeControllerTester();
        $response = $employeeControllerTester->delete(1);
        //dd($response);

        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);

        $employee = DB::table('employees')->where('id', 1)->first();
        $this->assertEquals('Jacky', $employee->first_name);
        $this->assertEquals('Shyu', $employee->last_name);

        $status = $response->getData()->status;
        $this->assertEquals(1, $status);
        $message = $response->getData()->msg;
        $expectedMessage = 'Sorry, employee cannot be deleted: Error from testing deleteEmployee function';
        $this->assertEquals($expectedMessage, $message);
    }
}

class EmployeeControllerTester extends EmployeeController
{
    public function deleteEmployee($employee) {
        throw new Exception('Error from testing deleteEmployee function');
    }
}