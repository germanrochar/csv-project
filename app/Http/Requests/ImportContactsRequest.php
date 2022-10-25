<?php

namespace App\Http\Requests;

use App\Rules\MappingValuesCannotBeDuplicated;
use App\Rules\MappingValuesCannotBeEmpty;
use App\Rules\RequiredMappingsMustBeProvided;
use Illuminate\Foundation\Http\FormRequest;

class ImportContactsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'csv_file' => 'required|mimes:csv,txt|max:4096',
            'mappings' => [
                'required',
                'json',
                new MappingValuesCannotBeEmpty,
                new RequiredMappingsMustBeProvided,
                new MappingValuesCannotBeDuplicated,
            ],
        ];
    }
}
