<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterWithExistingEmailTest extends TestCase
{
    use DatabaseMigrations;
    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        User::create([
            'name'          =>  'Ruel Rule',
            'email'         =>  'ruelrule05@gmail.com',
            'password'      =>  bcrypt('123Password_')
        ]);

        $response = $this->postJson('/api/register', [
            'name'                  =>  'Ruel Rule',
            'email'                 =>  'ruelrule05@gmail.com',
            'password'              =>  '123Password_',
            'password_confirmation' =>  '123Password_'
        ]);

        $response->assertStatus(422)
                    ->assertJson([
                        "errors" => [
                            "email" => [
                                "The email has already been taken."
                            ]
                        ]
                    ]);
    }
}
