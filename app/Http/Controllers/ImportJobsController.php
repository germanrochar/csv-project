<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetImportJobsRequest;
use App\Models\ImportJob;
use Illuminate\Http\JsonResponse;

class ImportJobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param GetImportJobsRequest $request
     * @return JsonResponse
     */
    public function index(GetImportJobsRequest $request): JsonResponse
    {
        $importJobs = ImportJob::query()
            ->importedToday($request->input('tz'))
            ->latest()
            ->get();

        return new JsonResponse($importJobs);
    }
}
