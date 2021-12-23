<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\HeadingRowImport;

class ContactsCsvScannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $headingRows = (new HeadingRowImport())->toArray(request()->file('csv_file'))[0];

        return new JsonResponse([
            'csv_fields' => $headingRows,
            'contacts_fields' => [
                'team_id',
                'name',
                'phone',
                'email',
                'sticky_phone_number_id'
            ]
        ]);
    }
}
