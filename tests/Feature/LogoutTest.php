<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends TestCase
{
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
            'password'  =>  'johndoepassword',
            'device_name'   =>  'sample_device'
        ]);

        $token = $response->json()['token'];
        
        $response = $this->withHeader('Authorization', 'Bearer wrong token' . $token)->postJson('/api/logout');

        $response->assertStatus(401)
                ->assertJson([
                    'message'   =>  'Unauthenticated.'
                ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->postJson('/api/logout');

        $response->assertStatus(200)
                ->assertJson([
                        "success" => true,
                        "message" => "You have been logged out."
                ]);


    }
}
