<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
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
}
