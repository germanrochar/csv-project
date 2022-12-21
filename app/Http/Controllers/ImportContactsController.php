<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportContactsRequest;
use App\Jobs\ImportContacts;
use App\Mappings;
use App\Models\ImportJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $uuid = Str::uuid()->toString();
        ImportJob::create([
            'uuid' => $uuid,
            'status' => ImportJob::STATUS_STARTED,
        ]);

        info('Import job uuid', ['id' => $uuid]);

        ImportContacts::dispatch($mappings, $csvPath, $uuid);

        return new JsonResponse(['message' => 'Import of contacts has started successfully.']);
    }
}
