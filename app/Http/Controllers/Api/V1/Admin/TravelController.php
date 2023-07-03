<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Travel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TravelRequest;
use App\Http\Resources\TravelResource;

class TravelController extends Controller
{
    /**
     * @group Admin
     * 
     * Add a new travel.
     *
     * This endpoint allows an admin to create a new travel
     * @bodyParam name string required A unique name for travel. Example: My Travel
     * @bodyParam description string required The description of travel.
     * @bodyParam is_public boolean required Specify travel is public or private. Example: true
     * @bodyParam number_of_days integer Specify how long travel will take in days. Example: 20
     * @response 201 {
     *  "id": d346fd9f-a86a-47e5-ba30-b3d25e69bfd4,
     *  "name": "Travel One",
     *  "slug": "Travel_One",
     *  "description": "This is travel one",
     *  "number_of_nights": 19,
     *  "number_of_days": 20,
     * }
     * @authenticated
     */
    public function store(TravelRequest $request)
    {
        $travel = Travel::create($request->validated());

        return new TravelResource($travel);
    }

     /**
     * @group Admin
     * 
     * Update existing travel.
     *
     * This endpoint allows an admin to update an existing travel
     * @bodyParam name string required A unique name for travel. Example: My Travel
     * @bodyParam description string required The description of travel.
     * @bodyParam is_public boolean required Specify travel is public or private. Example: true
     * @bodyParam number_of_days integer Specify how long travel will take in days. Example: 20
     * @response 200 {
     *  "id": d346fd9f-a86a-47e5-ba30-b3d25e69bfd4,
     *  "name": "Travel One",
     *  "slug": "Travel_One",
     *  "description": "This is travel one",
     *  "number_of_nights": 19,
     *  "number_of_days": 20,
     * }
     * @authenticated
     */
    public function update(Travel $travel, TravelRequest $request)
    {
        $travel->update($request->validated());

        return new TravelResource($travel);
    }
}
