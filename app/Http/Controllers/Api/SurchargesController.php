<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Structure\Abstract\Services\SurchargesServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SurchargesController extends Controller
{
    protected SurchargesServiceInterface $surchargesService;

    /**
     * @param SurchargesServiceInterface $surchargesService
     */
    public function __construct(SurchargesServiceInterface $surchargesService)
    {
        $this->surchargesService = $surchargesService;
    }

    public function getAll(Request $request): JsonResponse
    {
        return response()->json($this->surchargesService->getAll());
    }

    public function getAllFathers(Request $request): JsonResponse
    {
        return response()->json($this->surchargesService->getAllFathers());
    }

    public function group(Request $request): JsonResponse
    {
        return response()->json($this->surchargesService->group());
    }

    public function updateExcel(Request $request): JsonResponse
    {
        // Get the Excel file from the request
        $file = $request->file('excel');

        // Check the file extension
        $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        $allowed_extensions = ['xls', 'xlsx'];

        if (!in_array($extension, $allowed_extensions)) {
            return response()->json(['error' => 'The file extension is not allowed.']);
        }
        $data = [
            'answer' => $this->surchargesService->uploadDataFromExcel($file),
        ];
        return response()->json($data);
    }
}
