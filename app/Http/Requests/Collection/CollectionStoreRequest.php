<?php

namespace App\Http\Requests\Collection;

use Illuminate\Foundation\Http\FormRequest;

class CollectionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bank_id'           => 'exists:banks,id',
            'collection_amount' => 'required|numeric',
            'collection_type'   => 'required',
            'bank_name'         => 'nullable',
            'given_by'          => 'nullable',
            'remarks'           => 'nullable',
            'reference'         => 'nullable',
            'collection_date'   => 'required',
        ];
    }
}
