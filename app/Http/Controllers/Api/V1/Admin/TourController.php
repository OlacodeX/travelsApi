<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Travel;
use App\Http\Requests\TourRequest;
use App\Http\Resources\TourResource;

class TourController extends Controller
{
    /**
     * @group Admin
     * 
     * Add tour to a travel.
     *
     * This endpoint allows an admin to add tour to an existing travel
     * @bodyParam name string required The name of the tour. Example: My Tour
     * @bodyParam price number required The price of the tour.
     * @bodyParam starting_date string required The starting date of tour. Example: 2023-10-09
     * @bodyParam ending_date string The ending date of tour. Example: 2023-12-10
     * @response 201 {
     *  "id": d346fd9f-a86a-47e5-ba30-b3d25e69bfd4,
     *  "name": "Tour One",
     *  "starting_date": 2023-10-10,
     *  "ending_date": 2023-10-30,
     *  "price": 2023.45,
     * }
     * @authenticated
     */
    public function store(Travel $travel, TourRequest $request)
    {
        $tour = $travel->tours()->create($request->validated());

        return new TourResource($tour);
    }
}
