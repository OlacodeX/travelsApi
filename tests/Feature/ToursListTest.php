<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ToursListTest extends TestCase
{   
    use RefreshDatabase;
    /**
     * This tests if the tours returned are for the specified travel
     */
    public function test_tours_list_by_travel_slug_returns_correct_tours(): void
    {
        // create fake travel and tours
        $travel = Travel::factory()->create();
        $tour = Tour::factory()->create(['travel_id' => $travel->id]);
        
        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        // assert the id of returned tour is the created tour
        $response->assertJsonFragment(['id' => $tour->id]);
    }

    /**
     * This tests if the price is formatted correctly
     */
    public function test_tour_price_is_shown_correctly(): void
    {
        // create fake travel and tours
        $travel = Travel::factory()->create();
        $tour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 123.45,
        ]);
        
        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['price' => '123.45']);
    }

     /**
     * This tests if the data is returned correctly i.e paginated with 200 code
     */
    public function test_tours_list_returns_paginated_data_correctly(): void
    {
        
        $travel = Travel::factory()->create();
        $tour = Tour::factory(16)->create([
            'travel_id' => $travel->id
        ]);

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours');

        $response->assertStatus(200);
        // confirm endpoint returns json response with a key of data which contains 15 items.
        // 15 items because I am using laravel default pagination number of 15 and since I created 16 records,
        // the first page should return 15 records.
        $response->assertJsonCount(15, 'data');
        // Also test that number of paginated pages is 2 i.e last_page value is 2
        $response->assertJsonPath('meta.last_page', 2);
    }
}
