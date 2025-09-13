<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWardenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'initial_name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'nic' => 'required|string|unique:wardens,nic',
            'email' => 'required|email|unique:users,email',
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
            'dob' => 'required|date',
            'nationality' => 'required|string',
            'civil_status' => 'required|in:married,unmarried',
            'district' => 'required|string',
            'province' => 'required|string',
            'telephone_number' => 'required|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ];
    }
}
