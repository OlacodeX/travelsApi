<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Travel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TravelResource;

class TravelController extends Controller
{
     /**
     * @group Users
     * 
     * List of Public Travels.
     *
     * This endpoint return a list of all public travels
     * @response 200 {{
     *  "id": d346fd9f-a86a-47e5-ba30-b3d25e69bfd4,
     *  "name": "Travel One",
     *  "slug": "Travel_One",
     *  "description": "This is travel one",
     *  "number_of_nights": 19,
     *  "number_of_days": 20,
     * },
     * {
     *  "id": d346fd9f-a86a-47e5-ba30-b3d25e69bfd5,
     *  "name": "Travel Two",
     *  "slug": "Travel_Two",
     *  "description": "This is travel two",
     *  "number_of_nights": 19,
     *  "number_of_days": 20,
     * }
     */
    public function index()
    {
        $travels = Travel::where('is_public', true)->paginate();
        return TravelResource::collection($travels);
    }
}
