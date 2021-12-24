<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImportContactsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $csvFile = $request->file('csv_file');
        $array = $request->except('csv_file');
        \Log::info('Inputs', ['inputs' => $request->all(), 'testing' => gettype($array), 'another_test' => count($array)]);
        // Validate mappings
        // Call Importer and pass the file
    }
}
