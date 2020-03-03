<?php

namespace App\Http\Requests;

use App\School;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class MassDestroySchoolRequest extends FormRequest
{
    public function authorize()
    {
        return abort_if(Gate::denies('school_delete'), 403, '403 Forbidden') ?? true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:schools,id',
        ];
    }
}
