<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'application_no' => 'required|string|max:50|unique:applications,application_no',
            'customer_name'  => 'required|string|max:200',
            'customer_cnic'  => 'nullable|string|max:30',
            'phone'          => 'required|string|regex:/^[0-9]{11}$/|max:11',
            'address'        => 'nullable|string',
            'company_id'     => 'nullable|exists:companies,id',
            'subdivision_id' => 'nullable|exists:subdivisions,id',
            'meter_number'   => 'nullable|string|max:100',
            'connection_type'=> 'nullable|string|max:50',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'phone.required' => 'The phone number is required.',
            'phone.regex' => 'The phone number must be exactly 11 numeric digits.',
            'phone.max' => 'The phone number must be exactly 11 digits.',
            'application_no.unique' => 'This application number already exists.',
        ];
    }
}
