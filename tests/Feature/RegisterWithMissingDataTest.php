<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterWithMissingDataTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
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
}
