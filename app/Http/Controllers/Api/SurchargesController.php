<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JoinRequest;
use App\Structure\Abstract\Services\SurchargesServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 *
 */
class SurchargesController extends Controller
{
    /**
     * @var SurchargesServiceInterface
     */
    protected SurchargesServiceInterface $surchargesService;

    /**
     * We call interfaces because are registered in the service provider.
     *
     * @param SurchargesServiceInterface $surchargesService
     */
    public function __construct(SurchargesServiceInterface $surchargesService)
    {
        $this->surchargesService = $surchargesService;
    }

    /**
     * Get all surcharges, calling this function we can get the all surcharges in a list, without a group.
     *
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        return response()->json($this->surchargesService->getAll());
    }

    /**
     * @return JsonResponse
     */
    public function getAllFathers(): JsonResponse
    {
        return response()->json($this->surchargesService->getAllFathers());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function updateExcel(Request $request): JsonResponse
    {
        // Get the Excel file from the request
        $file = $request->file('excel');

        // Check if the file is null
        if ($file == null) return response()->json(['answer' => false]);

        // Check the file extension, we only allows Excel files
        $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        $allowed_extensions = ['xls', 'xlsx'];

        if (!in_array($extension, $allowed_extensions)) {
            return response()->json(['answer' => false]);
        }

        // Upload the data from the Excel file to the service and save the answer, I like to use an array because often I need add more data in the answer json
        $data = [
            'answer' => $this->surchargesService->uploadDataFromExcel($file),
        ];
        return response()->json($data);
    }

    /**
     * If the group algorithm is not enough, the user can join groups by the Surcharges ID
     * I'm validating the request using a custom JoinRequest
     *
     * @param JoinRequest $request
     * @return JsonResponse
     */
    public function joinGroups(JoinRequest $request): JsonResponse
    {
        if (!$request->validated())
            return response()->json(['answer' => false]);

        $idGroupA = (int)$request->get('idGroupA');
        $idGroupB = (int)$request->get('idGroupB');
        return response()->json([
            'answer' => $this->surchargesService->joinGroups($idGroupA, $idGroupB)
        ]);
    }
}
