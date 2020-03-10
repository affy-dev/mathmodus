<?php

namespace App\Http\Requests;

use App\Student;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class MassDestroyStudentRequest extends FormRequest
{
    public function authorize()
    {
        return abort_if(Gate::denies('student_delete'), 403, '403 Forbidden') ?? true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:students,id',
        ];
    }
}
