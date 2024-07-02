<?php

namespace App\Http\Requests\Api\address;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
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
            'name' => 'required',
            'full_address' => 'required',
            'phone' => 'required',
            'prov_id' => 'required',
            'city_id' => 'required',
            'district_id' => 'required',
            'postal_code' => 'required',
            // 'user_id' => 'required',
        ];
    }
}
