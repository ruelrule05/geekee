<?php

namespace Tests\Feature;

use App\Models\Tweet;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class TweetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_create_tweet()
    {
        $user = User::create([
            'name'          =>  'Jane Doe',
            'email'         =>  'janedoe@company.com',
            'password'      =>  bcrypt('janedoepassword')
        ]);

        $response = $this->postJson('/api/login', [
            'email'     =>  'janedoe@company.com',
            'password'  =>  'janedoepassword',
            'device_name'   =>  'sample_device'
        ]);

        $token = $response->json()['token'];

        $file = UploadedFile::fake()->create('first-tweet.jpg', 512, 'jpg');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->postJson('/api/tweets', [
            'content'       =>  'This is my first tweet.',
            'attachment'    =>  $file
        ]);

        $response->assertStatus(200)
                ->assertJson([
                        "success" => true,
                        "message" => "Tweet saved."
                ]);

        $this->assertDatabaseHas('tweets', [
            'user_id'       =>  $user->id,
            'content'       =>  'This is my first tweet.'
        ]);

        $this->assertFileExists($file);
    }

    public function test_can_update_tweet()
    {
        $user = User::create([
            'name'          =>  'Jane Doe',
            'email'         =>  'janedoe@company.com',
            'password'      =>  bcrypt('janedoepassword')
        ]);

        $response = $this->postJson('/api/login', [
            'email'     =>  'janedoe@company.com',
            'password'  =>  'janedoepassword',
            'device_name'   =>  'sample_device'
        ]);

        $token = $response->json()['token'];

        $file = UploadedFile::fake()->create('first-tweet.jpg', 512, 'jpg');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->postJson('/api/tweets', [
            'content'       =>  'This is my first tweet.',
            'attachment'    =>  $file
        ]);

        $response->assertStatus(200)
                ->assertJson([
                        "success" => true,
                        "message" => "Tweet saved."
                ]);

        $newTweet = $response->json()['tweet'];

        $tweet = Tweet::find($newTweet['id']);

        $tweet->update([
            'content'   =>  'This is my updated tweet.'
        ]);

        $this->assertDatabaseHas('tweets', [
            'id'        =>  $newTweet['id'],
            'content'   =>  'This is my updated tweet.'
        ]);
    }

    public function test_tweet_deleted()
    {
        $user = User::create([
            'name'          =>  'Jane Doe',
            'email'         =>  'janedoe@company.com',
            'password'      =>  bcrypt('janedoepassword')
        ]);

        $response = $this->postJson('/api/login', [
            'email'     =>  'janedoe@company.com',
            'password'  =>  'janedoepassword',
            'device_name'   =>  'sample_device'
        ]);

        $token = $response->json()['token'];

        $file = UploadedFile::fake()->create('first-tweet.jpg', 512, 'jpg');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->postJson('/api/tweets', [
            'content'       =>  'This is my first tweet.',
            'attachment'    =>  $file
        ]);

        $newTweet = $response->json()['tweet'];

        $tweet = Tweet::find($newTweet['id']);

        $this->assertDatabaseHas('tweets', [
            'id'            =>  $tweet->id,
            'content'       =>  'This is my first tweet.'
        ]);

        $tweet->delete();

        $this->assertDatabaseMissing('tweets', [
            'id'            =>  $tweet->id
        ]);
    }
}
