<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScanCsvRequest;
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
    public function index(ScanCsvRequest $request): JsonResponse
    {
        $headingRows = (new HeadingRowImport())->toArray($request->file('csv_file'))[0][0]; // TODO: improve this

        return new JsonResponse([
            'csvFields' => $headingRows,
            'contactsFields' => [
                'team_id',
                'name',
                'phone',
                'email',
                'sticky_phone_number_id'
            ]
        ]);
    }
}
