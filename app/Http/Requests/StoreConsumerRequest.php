<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConsumerRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'cnic' => 'required|string|unique:consumers|size:13',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'subdivision_id' => 'required|exists:subdivisions,id',
            'connection_type' => 'required|in:Domestic,Commercial,Industrial',
            'status' => 'required|in:active,disconnected,suspended',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'cnic.size' => 'The CNIC must be exactly 13 digits.',
            'connection_type.in' => 'Please select a valid connection type.',
        ];
    }
}
