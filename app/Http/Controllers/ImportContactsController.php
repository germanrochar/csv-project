<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportContactsRequest;
use App\Imports\ContactsImport;
use App\Mappings;
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

        $contactsMappings = new Mappings($contactFields, $csvFields);
        $customMappings = new Mappings($customContactFields, $customCsvFields);

        Excel::import(new ContactsImport($contactsMappings, $customMappings), $csvFile);
    }
}
