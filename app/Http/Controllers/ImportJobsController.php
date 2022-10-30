<?php

namespace App\Http\Controllers;

use App\Models\ImportJob;
use Illuminate\Http\JsonResponse;

class ImportJobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $importJobs = ImportJob::query()
            ->importedToday()
            ->orderBy('job_id', 'desc')
            ->get();

        return new JsonResponse($importJobs);
    }
}
