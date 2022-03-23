<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_cannot_register_if_incomplete_data()
    {
        $response = $this->postJson('/api/register', [
            'email'                 =>  'ruelrule05@gmail.com',
            'password'              =>  '123Password_',
            'password_confirmation' =>  '123Password_'
        ]);

        $response->assertStatus(422)
                    ->assertJson([
                        "errors" => [
                            "name" => [
                                "The name field is required."
                            ]
                        ]
                    ]);
    }

    public function test_user_cannot_register_duplicate_email()
    {
        User::create([
            'name'                  =>  'Ruel Rule',
            'email'                 =>  'ruelrule05@gmail.com',
            'password'              =>  bcrypt('123Password_')
        ]);
        
        $response = $this->postJson('/api/register', [
            'name'                  =>  'Ruel Rule',
            'email'                 =>  'ruelrule05@gmail.com',
            'password'              =>  '123Password_',
            'password_confirmation' =>  '123Password_'
        ]);

        $response->assertStatus(422)
                ->assertJson([
                    'errors'    =>  [
                        'email' =>  [
                            'The email has already been taken.'
                        ]
                    ]
        ]);
    }

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name'                  =>  'Ruel Rule',
            'email'                 =>  'ruelrule05@gmail.com',
            'password'              =>  '123Password_',
            'password_confirmation' =>  '123Password_'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success'   =>  true,
                    'message'   =>  'A new account has been registered.'
        ]);

        $this->assertDatabaseHas('users', [
            'email' =>  'ruelrule05@gmail.com'
        ]);
    }
}
