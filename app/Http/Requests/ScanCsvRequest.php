<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScanCsvRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'csv_file' => [
                'required',
                'mimes:csv,txt',
                'max:4096',
            ],
        ];
    }
}
