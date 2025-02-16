<?php

namespace Tests\Feature;


use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class BreweriesTest extends TestCase
{

    public function test_requires_authentication()
    {
        $response = $this->getJson('/api/breweries');
        $response->assertStatus(401);
    }


    public function test_allows_access_with_valid_token()
    {
        $user = User::where('username', 'root')->first();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/breweries');
        $response->assertStatus(200);
    }


    public function test_validates_sort_parameter()
    {
        $user = User::where('username', 'root')->first();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/breweries?sort=invalid:desc');
        $response->assertStatus(422)->assertJsonValidationErrors('sort');
    }


    public function test_validates_per_page_limit()
    {
        $user = User::where('username', 'root')->first();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/breweries?per_page=300'); // oltre il massimo di 200
        $response->assertStatus(422)->assertJsonValidationErrors('per_page');
    }
}
