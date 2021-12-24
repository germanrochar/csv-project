<?php

namespace App\Http\Requests;

use App\Rules\EncodedJsonValuesMustBeStrings;
use App\Rules\EncodedJsonValuesNotEmpty;
use App\Rules\RequiredContactFieldsMustBeProvided;
use Illuminate\Foundation\Http\FormRequest;

class ImportContactsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Allow all users for the moment
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'csv_file' => 'required|mimes:csv,txt|max:10240',
            'mapping_keys' => [
                'required',
                'string',
                new EncodedJsonValuesMustBeStrings,
                new EncodedJsonValuesNotEmpty
            ],
            'mapping_values' => [
                'required',
                'string',
                new EncodedJsonValuesMustBeStrings,
                new EncodedJsonValuesNotEmpty,
                new RequiredContactFieldsMustBeProvided
            ]
        ];
    }
}
