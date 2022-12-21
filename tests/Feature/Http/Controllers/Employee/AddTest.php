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

class AddTest extends TestCase
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

    public function test_store_for_adding_employee_without_img_name()
    {
        // Log in as administrator to access user list
        $response = $this->post('/api/login', ['email'=>'shyuadmin@yahoo.com', 'password'=>'12345678']);
        $response->assertStatus(200);

        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->call('POST', '/api/employees', [
            'first_name' => 'Jacky added',
            'middle_name' => 'M added',
            'last_name' => 'Shyu added',
            'email' => 'shyujackyadded@yahoo.com',
            'phone' => '214-680-8281-1',
            'birthday' => '01/01/2001',
            'ssn' => '123-45-6780',
            'gender' => 'Female',
            'position' => 'Manager added',
            'salary' => '60001',
            'address' => '123 Laurel Springs St. added',
            'address2' => 'Suite 100 added',
            'city' => 'Grapevine added',
            'state' => 'TX added',
            'zip' => '75115 added',
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

        $employee = DB::table('employees')->where('id', 2)->first();
        $this->assertEquals('Jacky added', $employee->first_name);
        $this->assertEquals('M added', $employee->middle_name);
        $this->assertEquals('Shyu added', $employee->last_name);
        $this->assertEquals('shyujackyadded@yahoo.com', $employee->email);
        $this->assertEquals('214-680-8281-1', $employee->phone);
        $this->assertEquals('01/01/2001', $employee->birthday);
        $this->assertEquals('123-45-6780', $employee->ssn);
        $this->assertEquals('Female', $employee->gender);
        $this->assertEquals('Manager added', $employee->position);
        $this->assertEquals('60001', $employee->salary);
        $this->assertEquals('123 Laurel Springs St. added', $employee->address);
        $this->assertEquals('Suite 100 added', $employee->address2);
        $this->assertEquals('Grapevine added', $employee->city);
        $this->assertEquals('TX added', $employee->state);
        $this->assertEquals('75115 added', $employee->zip);
        //$this->assertEquals('avatar.jpg', $employee->img);
        $this->assertEquals('01/01/2023', $employee->start_date);
        $this->assertEquals('01/01/2025', $employee->end_date);
     
        $status = $response->json()['status'];
        $this->assertEquals(1, $status);
    }
}
