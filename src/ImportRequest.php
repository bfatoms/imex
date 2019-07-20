<?php

namespace BfAtoms\Imex;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          "file" => 'required|mimes:csv,txt'
        ];
    }

    public function messages()
    {
        return [
            "file.required" => __('FILE_IS_REQUIRED'),
            "file.mimes" => __('CSV_ONLY')
        ];
    }
}
