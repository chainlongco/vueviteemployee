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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;

class EditTest extends TestCase
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
            'gender' => 'Male',
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

    public function test_retrieve_employee() {
        $response = $this->post('/api/login', ['email'=>'shyuadmin@yahoo.com', 'password'=>'12345678']);
        $response->assertStatus(200);

        // Test edit function in EmployeeController.php
        $response = $this->call('GET', '/api/employees/edit/1');
        
        $this->assertEquals(1, $response->getData()->id);
        $this->assertEquals('Jacky', $response->getData()->first_name);
        $this->assertEquals('M', $response->getData()->middle_name);
        $this->assertEquals('Shyu', $response->getData()->last_name);
        $this->assertEquals('shyujacky@yahoo.com', $response->getData()->email);
        $this->assertEquals('214-680-8281', $response->getData()->phone);
        $this->assertEquals('01/01/2000', $response->getData()->birthday);
        $this->assertEquals('123-45-6789', $response->getData()->ssn);
        $this->assertEquals('Male', $response->getData()->gender);
        $this->assertEquals('Manager', $response->getData()->position);
        $this->assertEquals('60000', $response->getData()->salary);
        $this->assertEquals('123 Laurel Springs St.', $response->getData()->address);
        $this->assertEquals('Suite 100', $response->getData()->address2);
        $this->assertEquals('Grapevine', $response->getData()->city);
        $this->assertEquals('TX', $response->getData()->state);
        $this->assertEquals('75115', $response->getData()->zip);
        $this->assertEquals('img.jpg', $response->getData()->img);
        $this->assertEquals('01/01/2022', $response->getData()->start_date);
        $this->assertEquals('01/01/2024', $response->getData()->end_date);
    }

    public function test_update_existing_customer_without_any_required_field()
    {
        // Log in as administrator to access user list
        $response = $this->post('/api/login', ['email'=>'shyuadmin@yahoo.com', 'password'=>'12345678']);
        $response->assertStatus(200);

        //$response = $this->call('POST', route('store-employee'), ['id'=>1, 'first_name'=>'', 'last_name'=>'', 'email'=>'', 'phone'=>'', 'birthday'=>'', 'ssn'=>'', 'gender'=>'', 'position'=>'', 'salary'=>'', 'address'=>'', 'city'=>'', 'state'=>'', 'zip'=>'', 'start_date'=>'']);
        $response = $this->call('POST', '/api/employees', ['id'=>1, 'first_name'=>'', 'last_name'=>'', 'email'=>'', 'phone'=>'', 'birthday'=>'', 'ssn'=>'', 'gender'=>'', 'position'=>'', 'salary'=>'', 'address'=>'', 'city'=>'', 'state'=>'', 'zip'=>'', 'start_date'=>'']);
        $response->assertStatus(200);
        //dd($response);

        $errors = $response->json()['error'];
        $this->assertEquals(14, count($errors));
        
        $message = $errors['first_name'][0];
        $this->assertEquals('The first name field is required.', $message);
        
        $message = $errors['last_name'][0];
        $this->assertEquals('The last name field is required.', $message);

        $message = $errors['email'][0];
        $this->assertEquals('The email field is required.', $message);

        $message = $errors['phone'][0];
        $this->assertEquals('The phone field is required.', $message);
        
        $message = $errors['birthday'][0];
        $this->assertEquals('The birthday field is required.', $message);

        $message = $errors['ssn'][0];
        $this->assertEquals('The ssn field is required.', $message);

        $message = $errors['gender'][0];
        $this->assertEquals('The gender field is required.', $message);

        $message = $errors['position'][0];
        $this->assertEquals('The position field is required.', $message);

        $message = $errors['salary'][0];
        $this->assertEquals('The salary field is required.', $message);

        $message = $errors['address'][0];
        $this->assertEquals('The address field is required.', $message);

        $message = $errors['city'][0];
        $this->assertEquals('The city field is required.', $message);

        $message = $errors['state'][0];
        $this->assertEquals('The state field is required.', $message);

        $message = $errors['zip'][0];
        $this->assertEquals('The zip field is required.', $message);

        $message = $errors['start_date'][0];
        $this->assertEquals('The hire date field is required.', $message);

        $status = $response->json()['status'];
        $this->assertEquals(0, $status);
    }

    public function test_update_existing_employee_with_invalid_email()
    {
        // Log in as administrator to access user list
        $response = $this->post('/api/login', ['email'=>'shyuadmin@yahoo.com', 'password'=>'12345678']);
        $response->assertStatus(200);

        $response = $this->call('POST', '/api/employees', ['id'=>1, 'first_name'=>'Jacky', 'last_name'=>'Shyu', 'email'=>'shyujacky', 'phone'=>'214-680-8281', 'birthday'=>'01/01/2000', 'ssn'=>'123456789', 'gender'=>'Male', 'position'=>'Manager', 'salary'=>'10', 'address'=>'123', 'city'=>'Desoto', 'state'=>'tx', 'zip'=>'75115', 'start_date'=>'01/01/2022']);
        $response->assertStatus(200);

        $errors = $response->json()['error'];
        $this->assertEquals(1, count($errors));
        $this->assertEquals('The email must be a valid email address.', $errors['email'][0]);

        $status = $response->json()['status'];
        $this->assertEquals(0, $status);
    }

    public function test_update_existing_employee_with_invalid_first_name_short_length()
    {
        // Log in as administrator to access user list
        $response = $this->post('/api/login', ['email'=>'shyuadmin@yahoo.com', 'password'=>'12345678']);
        $response->assertStatus(200);

        $response = $this->call('POST', '/api/employees', ['id'=>1, 'first_name'=>'J', 'last_name'=>'Shyu', 'email'=>'shyujacky@yahoo.com', 'phone'=>'214-680-8281', 'birthday'=>'01/01/2000', 'ssn'=>'123456789', 'gender'=>'Male', 'position'=>'Manager', 'salary'=>'10', 'address'=>'123', 'city'=>'Desoto', 'state'=>'tx', 'zip'=>'75115', 'start_date'=>'01/01/2022']);
        $response->assertStatus(200);

        $errors = $response->json()['error'];
        $this->assertEquals(1, count($errors));
        $this->assertEquals('The first name must be at least 2 characters.', $errors['first_name'][0]);

        $status = $response->json()['status'];
        $this->assertEquals(0, $status);
    }

    public function test_update_existing_employee_with_invalid_email_short_length()
    {
        // Log in as administrator to access user list
        $response = $this->post('/api/login', ['email'=>'shyuadmin@yahoo.com', 'password'=>'12345678']);
        $response->assertStatus(200);

        $response = $this->call('POST', '/api/employees', ['id'=>1, 'first_name'=>'Jacky', 'last_name'=>'Shyu', 'email'=>'s@yahoo.com', 'phone'=>'214-680-8281', 'birthday'=>'01/01/2000', 'ssn'=>'123456789', 'gender'=>'Male', 'position'=>'Manager', 'salary'=>'10', 'address'=>'123', 'city'=>'Desoto', 'state'=>'tx', 'zip'=>'75115', 'start_date'=>'01/01/2022']);
        $response->assertStatus(200);

        $errors = $response->json()['error'];
        $this->assertEquals(1, count($errors));
        $this->assertEquals('The email must be at least 15 characters.', $errors['email'][0]);

        $status = $response->json()['status'];
        $this->assertEquals(0, $status);
    }

    public function test_store_for_existing_employee_without_img_name()
    {
        // Log in as administrator to access user list
        $response = $this->post('/api/login', ['email'=>'shyuadmin@yahoo.com', 'password'=>'12345678']);
        $response->assertStatus(200);

        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->call('POST', '/api/employees', [
            'id'=>1, 
            'first_name' => 'Jacky updated',
            'middle_name' => 'M updated',
            'last_name' => 'Shyu updated',
            'email' => 'shyujackyupdated@yahoo.com',
            'phone' => '214-680-8281-1',
            'birthday' => '01/01/2001',
            'ssn' => '123-45-6780',
            'gender' => 'Other',
            'position' => 'Manager updated',
            'salary' => '60001',
            'address' => '123 Laurel Springs St. updated',
            'address2' => 'Suite 100 updated',
            'city' => 'Grapevine updated',
            'state' => 'TX updated',
            'zip' => '75115 updated',
            'img' => $file,
            'start_date' => '01/01/2023',
            'end_date' => '01/01/2025',
        ]);
        $response->assertStatus(200);

        // Assert the file was stored...
        //Storage::disk('public/images')->assertExists('avatar.jpg');
        //Storage::disk('avatars')->assertExists($file->hashName());
        //Storage::assertExists('public/images/' .$file->hashName());
        //Storage::assertExists('post_images/' . $file->hashName());
        // Assert a file does not exist...
        //Storage::disk('avatars')->assertMissing('missing.jpg');

        $employee = DB::table('employees')->where('id', 1)->first();
        $this->assertEquals('Jacky updated', $employee->first_name);
        $this->assertEquals('M updated', $employee->middle_name);
        $this->assertEquals('Shyu updated', $employee->last_name);
        $this->assertEquals('shyujackyupdated@yahoo.com', $employee->email);
        $this->assertEquals('214-680-8281-1', $employee->phone);
        $this->assertEquals('01/01/2001', $employee->birthday);
        $this->assertEquals('123-45-6780', $employee->ssn);
        $this->assertEquals('Other', $employee->gender);
        $this->assertEquals('Manager updated', $employee->position);
        $this->assertEquals('60001', $employee->salary);
        $this->assertEquals('123 Laurel Springs St. updated', $employee->address);
        $this->assertEquals('Suite 100 updated', $employee->address2);
        $this->assertEquals('Grapevine updated', $employee->city);
        $this->assertEquals('TX updated', $employee->state);
        $this->assertEquals('75115 updated', $employee->zip);
        //$this->assertEquals('avatar.jpg', $employee->img);
        $this->assertEquals('01/01/2023', $employee->start_date);
        $this->assertEquals('01/01/2025', $employee->end_date);
     
        $status = $response->json()['status'];
        $this->assertEquals(1, $status);
    }

    public function test_store_for_existing_employee_for_img_name()
    {
        // Log in as administrator to access user list
        $response = $this->post('/api/login', ['email'=>'shyuadmin@yahoo.com', 'password'=>'12345678']);
        $response->assertStatus(200);

        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $fakeRequest = Request::create('/', 'POST', [
            'id'=>1, 
            'first_name' => 'Jacky updated',
            'middle_name' => 'M updated',
            'last_name' => 'Shyu updated',
            'email' => 'shyujackyupdated@yahoo.com',
            'phone' => '214-680-8281-1',
            'birthday' => '01/01/2001',
            'ssn' => '123-45-6780',
            'gender' => 'Other',
            'position' => 'Manager updated',
            'salary' => '60001',
            'address' => '123 Laurel Springs St. updated',
            'address2' => 'Suite 100 updated',
            'city' => 'Grapevine updated',
            'state' => 'TX updated',
            'zip' => '75115 updated',
            'img' => $file,
            'start_date' => '01/01/2023',
            'end_date' => '01/01/2025',
        ]); //************** Very important fake Request

        $employeeControllerForEditTester = new EmployeeControllerForEditTester();
        $response = $employeeControllerForEditTester->store($fakeRequest);
    
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);

        //$content = json_decode($response->content());
        //$status = $content->status;
        $status = $response->getData()->status;
        $this->assertEquals(1, $status);

        // Assert the file was stored...
        //Storage::disk('public/images')->assertExists('avatar.jpg');
        //Storage::disk('avatars')->assertExists($file->hashName());
        //Storage::assertExists('public/images/' .$file->hashName());
        //Storage::assertExists('post_images/' . $file->hashName());
        // Assert a file does not exist...
        //Storage::disk('avatars')->assertMissing('missing.jpg');

        $employee = DB::table('employees')->where('id', 1)->first();
        $this->assertEquals('avatar.jpg', $employee->img);
    }
}

class EmployeeControllerForEditTester extends EmployeeController
{
    protected function retrieveRandomImageName($request) {
        return 'avatar.jpg';
    }

    protected function handleImage(Request $request, String $imageName, String $oldImageName) {
    }
}
