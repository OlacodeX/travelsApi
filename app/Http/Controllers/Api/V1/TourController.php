<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Travel;
use App\Http\Controllers\Controller;
use App\Http\Resources\TourResource;
use App\Http\Requests\ToursListRequest;

class TourController extends Controller
{
     /**
     * @group Users
     * 
     * List of Tours.
     *
     * This endpoint return all tours for a particular travel
     * You can also filter the result with price, starting date and ending date
     * @queryParam sortBy string Field to sort by. Accepts only price as value
     * @queryParam sortOrder string Order of sorting. Accepts either asc or desc as value
     * @response 200 {{
     *  "id": d346fd9f-a86a-47e5-ba30-b3d25e69bfd4,
     *  "name": "Tour One",
     *  "starting_date": 2023-10-10,
     *  "ending_date": 2023-10-30,
     *  "price": 2023.45,
     * },
     * {
     *  "id": d346fd9f-a86a-47e5-ba30-b3d25e69bfd5,
     *  "name": "Tour Two",
     *  "starting_date": 2023-10-10,
     *  "ending_date": 2023-10-30,
     *  "price": 2023.45,
     * }
     * }
     */
    public function index(Travel $travel, ToursListRequest $request)
    {
        $tours = $travel->tours()
            ->when($request->priceFrom, function ($query) use ($request) {
                $query->where('price', '>=', $request->priceFrom * 100);
            })
            ->when($request->priceTo, function ($query) use ($request) {
                $query->where('price', '<=', $request->priceTo * 100);
            })
            ->when($request->dateFrom, function ($query) use ($request) {
                $query->where('starting_date', '>=', $request->dateFrom);
            })
            ->when($request->dateTo, function ($query) use ($request) {
                $query->where('ending_date', '<=', $request->dateTo);
            })
            ->when($request->sortBy && $request->sortOrder, function ($query) use ($request) {
                $query->orderBy($request->sortBy, $request->sortOrder);
            })
            ->orderBy('starting_date')
            ->paginate();
        return TourResource::collection($tours);
    }
}
