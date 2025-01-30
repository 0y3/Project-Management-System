<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

class ProjectRequest extends FormRequest
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
        $id = isset($this->project_id)?$this->project_id:$this->id;
        return [
            // 'name' =>  'required|string|unique:projects,name,'.$id.',id',
            'name' =>  'required|string',
            'description' =>  'nullable|string',
            'start_date' =>  'required|date',
            'end_date' =>  'required|date',
            'assignee_id' => 'nullable|integer',
        ];
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
