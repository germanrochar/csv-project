<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportContactsRequest;
use App\Imports\ContactsImport;
use App\Mappings;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;

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
        $mappings = json_decode($request->input('mappings'), TRUE);

        $mappings = new Mappings(
            array_values($mappings),
            array_keys($mappings),
        );
        try {
            Excel::import(new ContactsImport($mappings), $csvFile);
        } catch (QueryException $e) {
            return new JsonResponse([
                'message' => 'Please check the data types of your mapped fields in csv file. Some data types does not match.'
            ], 400);
        }

        return new JsonResponse(['message' => 'Contacts imported successfully.']);
    }
}
