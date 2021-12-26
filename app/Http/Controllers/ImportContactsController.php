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
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(ImportContactsRequest $request)
    {
        $csvFile = $request->file('csv_file');

        $contactsMappings = new Mappings(
            json_decode($request->input('contact_fields')),
            json_decode($request->input('csv_fields'))
        );
        $customMappings = new Mappings(
            json_decode($request->input('custom_contact_fields')),
            json_decode($request->input('custom_csv_fields'))
        );

        try {
            Excel::import(new ContactsImport($contactsMappings, $customMappings), $csvFile);
        } catch (QueryException $exception) {
            return new JsonResponse([
                'message' => 'Please check the data types of your mapped fields in csv file. Some data types doesn\'t match.'
            ], 400);
        }

        return new JsonResponse('Contacts imported successfully.');
    }
}
