<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginTest extends TestCase
{


    public function test_successful_login()
    {


        $response = $this->postJson('/api/login', [
            'username' => 'root',
            'password' => 'password',
        ]);


        $response->assertStatus(200);
    }


    public function test_login_with_non_existent_username()
    {
        $response = $this->postJson('/api/login', [
            'username' => 'nonexistent_user',
            'password' => 'password',
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors('username');
    }


    public function test_login_missing_username()
    {
        $response = $this->postJson('/api/login', [
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('username');
    }


    public function test_login_missing_password()
    {

        $response = $this->postJson('/api/login', [
            'username' => 'root',
        ]);


        $response->assertStatus(422)
            ->assertJsonValidationErrors('password');
    }
}
