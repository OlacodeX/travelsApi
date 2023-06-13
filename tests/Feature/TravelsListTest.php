<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Travel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TravelsListTest extends TestCase
{ 
    /**
    * The use refresh database trait is required if the test involves testing the db
    * It will run migrate refresh after every test hence we need to set up a fake db
    * This way we won't be tampering with our real db
    * Finally, uncomment these lines in phpunit.xml
    *
        *<env name="DB_CONNECTION" value="sqlite"/>
        *<env name="DB_DATABASE" value=":memory:"/>
    */
    use RefreshDatabase;

    /**
     * This tests if the data is returned correctly i.e paginated with 200 code
     */
    public function test_travels_list_returns_paginated_data_correctly(): void
    {
        // create fake new 16 records
        // Here we are overriding the is_public column from the factory setting all to true
        Travel::factory(16)->create(['is_public' => true]);
        // test using the fake db
        $response = $this->get('/api/v1/travels');

        $response->assertStatus(200);
        // confirm endpoint returns json response with a key of data which contains 15 items.
        // 15 items because I am using laravel default pagination number of 15 and since I created 16 records,
        // the first page should return 15 records.
        $response->assertJsonCount(15, 'data');
        // Also test that number of paginated pages is 2 i.e last_page value is 2
        $response->assertJsonPath('meta.last_page', 2);
    }

      /**
     * This tests if the data is returned correctly i.e paginated with 200 code
     */
    public function test_travels_list_shows_only_public_records(): void
    {
        // create fake one public and private record each
        $publicTravel = Travel::factory()->create(['is_public' => true]);
        Travel::factory()->create(['is_public' => false]);
        // test using the fake db
        $response = $this->get('/api/v1/travels');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        // Assert that returned record is only public
        $response->assertJsonPath('data.0.name', $publicTravel->name);
    }
}
