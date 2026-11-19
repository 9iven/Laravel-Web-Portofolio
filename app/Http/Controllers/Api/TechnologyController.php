<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Technology;
use Illuminate\Http\JsonResponse;

class TechnologyController extends Controller
{
    public function index(): JsonResponse
    {
        $technologies = Technology::orderBy('name')->get(['id', 'name', 'slug']);

        return response()->json([
            'data' => $technologies,
        ]);
    }
}
