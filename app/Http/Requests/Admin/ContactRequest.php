<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'name'          => 'required',
            'email'         => 'nullable|email',
            'phone'       => 'nullable|numeric',
            'address'        => 'nullable',
            'city'          => 'nullable',
            'code_post'      => 'nullable|numeric',
            'code_contact'   => 'nullable',
            'currency'     => 'nullable',
            'nik'           => 'nullable|numeric',
            'person_contact' => 'nullable',
            'website'       => 'nullable',
            'customer'     => 'required_without_all:supplier,employee,client,officer',
            'supplier'       => 'required_without_all:customer,employee,client,officer',
            'employee'      => 'required_without_all:customer,supplier,client,officer',
        ];
    }
}
