<?php

namespace App\Http\Requests;

use App\School;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSchoolRequest extends FormRequest
{
    public function authorize()
    {
        return \Gate::allows('school_edit');
    }

    public function rules()
    {
        return [
            'school_name' => [
                'required',
            ],
            'school_pincode' => [
                'max:6'
            ],
            'school_phone'  => [
                'max:10'
            ],
        ];
    }
}
