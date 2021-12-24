<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportContactsRequest;
use App\Imports\ContactsImport;
use App\Imports\CustomAttributeImport;
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
        $mappingKeys = json_decode($request->input('mapping_keys'));
        $mappingValues = json_decode($request->input('mapping_values'));

        $mappings = [];
        foreach ($mappingKeys as $index => $key) {
            $mappings[$key] = $mappingValues[$index];
        }

        $contactsColumns = Contact::getColumnsAllowedForImport();
        $contactsMappings = array_flip(array_intersect($mappings, $contactsColumns));
        $customMappings = array_flip(array_diff($mappings, $contactsColumns));

        \Log::info('Mappings', ['contactsMappings' => $contactsMappings, 'customMappings' => $customMappings]);


        // Reading the same file twice is not great for performance but, I wasn't sure how much the performance
        // was a priority for this exercise. So, I decided to create two import classes to make the code cleaner.
        Excel::import(new ContactsImport($contactsMappings, $customMappings), $csvFile);
//        Excel::import(new CustomAttributeImport($customMappings), $csvFile);
    }
}
