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

class SignInOutTest extends TestCase
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

        User::create([
            'name'=>'Jacky Shyu',
            'email'=>'shyujacky@yahoo.com',
            'password'=>Hash::make('12345678')
        ]);
    }

    // ****Log In Start****
    public function test_login_form()
    {
        $response = $this->get('users/login');
        $response->assertStatus(200);
        // Call __invoke() function in UserController.php, then view('users.index')
        $response->assertSee('Chinamax Users');
    }

    public function test_sign_in_without_email_password()
    {
        $response = $this->call('POST', '/api/login', ['email'=>'', 'password'=>'']);
        $response->assertStatus(200);

        $errors = $response->json()['error'];
        $status = $response->json()['status'];

        $this->assertEquals(2, count($errors));
        $this->assertEquals('The email field is required.', $errors['email'][0]);
        $this->assertEquals('The password field is required.', $errors['password'][0]);
        $this->assertEquals(0, $status);
    }

    public function test_sign_in_without_email()
    {
        $response = $this->call('POST', '/api/login', ['email'=>'', 'password'=>'12345678']);
        $response->assertStatus(200);
        
        $errors = $response->json()['error'];
        $status = $response->json()['status'];
       
        $this->assertEquals(1, count($errors));
        $this->assertEquals('The email field is required.', $errors['email'][0]);
        $this->assertEquals(0, $status);
    }

    public function test_sign_in_without_password()
    {
        $response = $this->call('POST', '/api/login', ['email'=>'shyujacky@yahoo.com', 'password'=>'']);
        $response->assertStatus(200);
       
        $errors = $response->json()['error'];
        $this->assertEquals(1, count($errors));
        $this->assertEquals('The password field is required.', $errors['password'][0]);

        $status = $response->json()['status'];
        $this->assertEquals(0, $status);
    }

    public function test_sign_in_with_invalid_email()
    {
        $response = $this->call('POST', '/api/login', ['email'=>'shyujacky', 'password'=>'12345678']);
        $response->assertStatus(200);
        $errors = $response->json()['error'];
        $this->assertEquals(1, count($errors));
        $this->assertEquals('The email must be a valid email address.', $errors['email'][0]);

        $status = $response->json()['status'];
        $this->assertEquals(0, $status);
    }

    public function test_sign_in_email_password_not_match()
    {
        $response = $this->post('/api/login', ['email'=>'shyujacky@yahoo.com', 'password'=>'1234567']);
        $response->assertStatus(200);

        $status = $response->json()['status'];
        $this->assertEquals(1, $status);

        $message = $response->json()['msg'];
        $this->assertEquals('Email and Password not matched!', $message);
    }

    public function test_sign_in_not_success_to_access_employee_information()
    {
        $response = $this->post('/api/login', ['email'=>'shyujacky@yahoo.com', 'password'=>'12345678']);
        $response->assertStatus(200);

        $status = $response->json()['status'];
        $this->assertEquals(3, $status);
        
        $message = $response->json()['msg'];
        $this->assertEquals('This user is not allowed to access employee information', $message);
    }

    public function test_sign_in_success_to_access_employee_information()
    {
        $response = $this->post('/api/login', ['email'=>'shyuadmin@yahoo.com', 'password'=>'12345678']);
        $response->assertStatus(200);

        $status = $response->json()['status'];
        $this->assertEquals(2, $status);

        $user = Session::get('user');
        $this->assertEquals('Admin Shyu', $user->name);
        $this->assertEquals('shyuadmin@yahoo.com', $user->email);
        $this->assertTrue(Hash::check('12345678', $user->password));
    }
    // ****Log In End****

    // ****Log Out Start****
    public function test_log_out()
    {
        $response = $this->post('/api/login', ['email'=>'shyuadmin@yahoo.com', 'password'=>'12345678']);
        $response->assertStatus(200);
        $user = Session::get('user');
        $this->assertEquals('Admin Shyu', $user->name);
        $this->assertEquals('shyuadmin@yahoo.com', $user->email);
        $this->assertTrue(Hash::check('12345678', $user->password));

        $response = $this->get('api/logout');
        $user = Session::get('user');
        $this->assertNull($user);
        $response->assertRedirect('/users/login');
    }
    // ****Log Out End****
}
