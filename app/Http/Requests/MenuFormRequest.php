<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class MenuFormRequest extends FormRequest
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
            'name' =>  'required',
            'slug' => ['nullable', 'alpha'],
            'route' => ['nullable'],
            'parent_id' => 'nullable|int',
            'description' => ['required', 'regex:/^[0-9A-Za-z.,\-_\s]+$/'],
            'created_by' => 'required|int',
        ];
    }

    /**
     * Modified data before validation
     */
    protected function prepareForValidation() 
    {
        $this->merge([
            'created_by' => auth()->user()->id,
            'parent_id' => request()->parent_id??0,
            'route' => request()->route??'#'
        ]);
    } 

    /**
     *  Send validation error response back to HTTP
    */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $response = response()->json([
            'status' => 'error',
            'message' => 'Invalid data send',
            'errors' => $errors->messages(),
        ], 422);

        throw new HttpResponseException($response);
    }
}
