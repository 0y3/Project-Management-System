<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
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
        $id = isset($this->user_id)?$this->user_id:$this->id;
        return [
            'email' =>  'required|email|unique:users,email,'.$id.',id',
            'name' =>  'required|string',
            // 'last_name' =>  'required|alpha',
            // 'mobile_phone' =>  'required|numeric|min:14',
            // 'password' => [
            //     'required',
            //     'string',
            //     Password::min(8)
            //         ->mixedCase()
            //         ->numbers()
            //         ->symbols()
            //         ->uncompromised(),
            //     'confirmed'
            // ]
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
