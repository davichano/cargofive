<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Structure\Services\SurchargesService;
use Illuminate\Http\Request;

class SurchargesController extends Controller
{
    protected $surchargesService;

    /**
     * @param SurchargesService $surchargesService
     */
    public function __construct(SurchargesService $surchargesService)
    {
        $this->surchargesService = $surchargesService;
    }

    public function getAll(Request $request)
    {
        return response()->json($this->surchargesService->getAll());
    }

    public function getAllFathers(Request $request)
    {
        return response()->json($this->surchargesService->getAllFathers());
    }

    public function group(Request $request)
    {
        return response()->json($this->surchargesService->group());
    }

}
