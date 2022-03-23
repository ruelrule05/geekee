<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_cannot_login_if_incomplete_data()
    {
        User::create([
            'name'          =>  'John Doe',
            'email'         =>  'johndoe@company.com',
            'password'      =>  bcrypt('johndoepassword')
        ]);

        $response = $this->postJson('/api/login', [
            'email'     =>  'johndoe@company.com',
            'password'  =>  'mypassword'
        ]);

        $response->assertStatus(422)
                ->assertJson([
                    'errors'    =>  [
                        'device_name'   =>  [
                            'The device name field is required.'
                        ]
                    ]
                ]);
    }

    public function test_user_cannot_login_if_invalid_credentials()
    {
        User::create([
            'name'          =>  'John Doe',
            'email'         =>  'johndoe@company.com',
            'password'      =>  bcrypt('johndoepassword')
        ]);

        $response = $this->postJson('/api/login', [
            'email'     =>  'johndoe@company.com',
            'password'  =>  'incorrectpassword',
            'device_name'   =>  'johndoedevice'
        ]);

        $response->assertStatus(401)
                ->assertJson([
                    'success'   =>  false,
                    'message'   =>  'Invalid credentials.'
                ]);
    }

    public function test_user_can_login()
    {
        User::create([
            'name'          =>  'John Doe',
            'email'         =>  'johndoe@company.com',
            'password'      =>  bcrypt('johndoepassword')
        ]);

        $response = $this->postJson('/api/login', [
            'email'     =>  'johndoe@company.com',
            'password'  =>  'johndoepassword',
            'device_name'   =>  'johndoedevice'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    "success" => true
                ]);
    }

    public function test_user_cannot_logout_without_correct_token()
    {
        User::create([
            'name'          =>  'John Doe',
            'email'         =>  'johndoe@company.com',
            'password'      =>  bcrypt('johndoepassword')
        ]);

        $response = $this->postJson('/api/login', [
            'email'     =>  'johndoe@company.com',
            'password'  =>  'johndoepassword',
            'device_name'   =>  'sample_device'
        ]);

        $token = $response->json()['token'];
        
        $response = $this->withHeader('Authorization', 'Bearer wrong token' . $token)->postJson('/api/logout');

        $response->assertStatus(401)
                ->assertJson([
                    'message'   =>  'Unauthenticated.'
                ]);
    }
}
