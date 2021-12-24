<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportContactsRequest;
use App\Imports\ContactsImport;
use App\Models\Contact;
use Maatwebsite\Excel\Facades\Excel;

class ImportContactsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImportContactsRequest $request)
    {
        $csvFile = $request->file('csv_file');
        $contactFields = json_decode($request->input('contact_fields'));
        $csvFields = json_decode($request->input('csv_fields'));

        $customContactFields = json_decode($request->input('custom_contact_fields'));
        $customCsvFields = json_decode($request->input('custom_csv_fields'));

        $contactsMappings = [];
        foreach ($csvFields as $index => $key) {
            $contactsMappings[$key] = $contactFields[$index];
        }

        $customContactMappings = [];
        foreach ($customCsvFields as $index => $key) {
            $customContactMappings[$key] = $customContactFields[$index];
        }

        $contactsMappings = array_flip($contactsMappings);
        $customMappings = array_flip($customContactMappings);

        \Log::info('Mappings', ['contactsMappings' => $contactsMappings, 'customMappings' => $customMappings]);

        // Reading the same file twice is not great for performance but, I wasn't sure how much the performance
        // was a priority for this exercise. So, I decided to create two import classes to make the code cleaner.
        Excel::import(new ContactsImport($contactsMappings, $customMappings), $csvFile);
    }
}
