<?php

namespace App\Http\Requests;

use App\School;
use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolRequest extends FormRequest
{
    public function authorize()
    {
        return \Gate::allows('school_create');
    }

    public function rules()
    {
        return [
            'school_name'     => [
                'required',
                'unique:schools'
            ],
            'school_pincode' => [
                'required',
                'max:6'
            ],
            'school_phone'  => [
                'required',
                'max:10'
            ],
        ];
    }
}
