<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
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
    }
}
