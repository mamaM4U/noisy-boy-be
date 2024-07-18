<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index(): JsonResponse
    {
        $listings = Listing::withCount('transactions')->orderBy('transactions_count', 'desc')->paginate();
        return response()->json([
            'success' => true,
            ' message' => 'Listing retrieved successfully',
            'data' => $listings
        ]);
    }

    public function show(Listing $listing): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail Listing retrieved successfully',
            'data' => $listing
        ]);
    }   
}
