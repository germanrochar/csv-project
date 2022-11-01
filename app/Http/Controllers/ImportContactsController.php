<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportContactsRequest;
use App\Jobs\ImportContacts;
use App\Mappings;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ImportContactsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param ImportContactsRequest $request
     * @return JsonResponse
     */
    public function store(ImportContactsRequest $request): JsonResponse
    {
        $csvFile = $request->file('csv_file');
        $mappingsInput = json_decode($request->input('mappings'), TRUE);

        $csvPath = Storage::putFile('/csv/files', $csvFile);

        $mappings = new Mappings(
            array_values($mappingsInput),
            array_keys($mappingsInput),
        );

        ImportContacts::dispatch($mappings, $csvPath);

        return new JsonResponse(['message' => 'Import of contacts has started successfully.']);
    }
}
