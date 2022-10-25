<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScanCsvRequest;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\HeadingRowImport;

class ContactsCsvScannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ScanCsvRequest $request
     * @return JsonResponse
     */
    public function index(ScanCsvRequest $request): JsonResponse
    {
        $headingRows = (new HeadingRowImport())->toArray($request->file('csv_file'))[0][0];

        if (count($headingRows) !== count(array_unique($headingRows))) {
            return new JsonResponse(['message' => 'Duplicate headings were found in csv file.'], 400);
        }

        return new JsonResponse([
            'csvFields' => $headingRows,
            'contactsFields' => Contact::getColumnsAllowedToImport()
        ]);
    }
}
