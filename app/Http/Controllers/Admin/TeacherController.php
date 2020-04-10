<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTeacherRequest;
use App\Http\Requests\StoreSchoolRequest;
use App\Http\Requests\UpdateSchoolRequest;
use App\Http\Requests\AssignPrincipalRequest;
use App\Teacher;
use App\User;
use App\Student;
use App\School;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    const GENDER = [
        1 => 'Male',
        2 => 'Female'
    ];

    const BLOOD_GROUP = [
        1 => 'A+',
        2 => 'O+',
        3 => 'B+',
        4 => 'AB+',
        5 => 'A-',
        6 => 'O-',
        7 => 'B-',
        8 => 'AB-',
    ];

    const TEACHER_ROLE = 4;

    public function index()
    {
        abort_unless(\Gate::allows('teachers_access'), 403);
        $teachers = \DB::table('users')
            ->join('role_user', function($join) {
                $join->on('users.id', '=', 'role_user.user_id');
                $join->where('role_user.role_id', self::TEACHER_ROLE);
            })
            ->join('teachers', 'users.id', '=', 'teachers.user_id')
            ->leftJoin('schools', 'schools.id', '=', 'teachers.school_id')
            ->get(['users.id as userId', 'schools.school_name as school_name', 'users.name as name', 'users.email as email', 'teachers.dob as dob', 'teachers.designation as designation', 'teachers.id as teacherId', 'teachers.phone_no as phone_no']);
        
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        abort_unless(\Gate::allows('teachers_create'), 403);
        $gender = self::GENDER;
        $blood_group = self::BLOOD_GROUP;
        $users = \DB::table('users')
            ->join('role_user', function($join) {
                $join->on('users.id', '=', 'role_user.user_id');
                $join->where('role_user.role_id', self::TEACHER_ROLE);
            })
            ->whereNull('users.deleted_at')
            ->get();

        return view('admin.teachers.create', compact('gender', 'blood_group', 'users'));
    }

    public function store(Request $request)
    {
        abort_unless(\Gate::allows('teachers_create'), 403);
        $rules = [
            'user_id' => 'required|unique:teachers,user_id',
            'dob' => 'required|min:10|max:10',
            'gender' => 'required|integer',
            'email' => 'email|max:255|unique:teachers,email',
            'phone_no' => 'required|max:10',
            'address' => 'required|max:255',
        ];

        $this->validate($request, $rules);
        $userName = User::find($request->input('user_id'));
        $teacherData = $request->all();
        $teacherData['name'] = $userName->name;
        Teacher::create($teacherData);

        return redirect()->route('admin.teachers.index');
    }

    public function edit(Request $request, $userId)
    {
        abort_unless(\Gate::allows('teachers_edit'), 403);
        $teacher = \DB::table('users')
            ->join('teachers', function($join) use ($userId) {
                $join->on('users.id', '=', 'teachers.user_id');
                $join->where('users.id','=', $userId);
            })
            ->whereNull('users.deleted_at')
            ->get(['users.id as userId', 'teachers.id as teacherId', 'users.name as name', 'teachers.designation as designation', 'teachers.qualification as qualification', 'teachers.dob as dob', 'teachers.gender as gender', 'teachers.email as email', 'teachers.phone_no as phone_no', 'teachers.address as address', 'teachers.joining_date as joining_date']);

        $name = '';
        $designation = '';
        $qualification = '';
        $dob = '';
        $gender = '';
        $email = '';
        $phone_no = '';
        $address = '';
        $joining_date = '';
        $teacherId = '';
        foreach ($teacher as $key => $value) {
            $name = $value->name;
            $designation = $value->designation;
            $qualification = $value->qualification;
            $dob = $value->dob;
            $genderValue = $value->gender;
            $email = $value->email;
            $phone_no = $value->phone_no;
            $address = $value->address;
            $joining_date = $value->joining_date;
            $teacherId = $value->teacherId;
        }

        $gender = self::GENDER;
        $blood_group = self::BLOOD_GROUP;
        return view('admin.teachers.edit', compact('name', 'email', 'dob', 'designation', 'qualification', 'genderValue', 'email', 'phone_no', 'address', 'joining_date', 'teacherId', 'userId', 'gender'));
    }

    public function update(Request $request, $teacherId)
    {
        abort_unless(\Gate::allows('teachers_edit'), 403);
        $inputData = $request->except(['_token','_method']);
        $name = $inputData['name'];
        $email = $inputData['email'];
        $userId = $inputData['userId'];
        
        $rules = [
            'name'      => 'required|unique:users,id',
            'dob'       => 'required|min:10|max:10',
            'gender'    => 'required|integer',
            'email'     => 'unique:users,email,'.$userId,
            'phone_no'  => 'required|max:10',
            'address'   => 'required|max:255',
        ];

        $this->validate($request, $rules);
        User::where('id', $userId)->update(['name'=>$name, 'email'=>$email]); // update USER table data
        unset($inputData['name']);
        unset($inputData['userId']);
        Teacher::where('id', $teacherId)->update($inputData);
        return redirect()->route('admin.teachers.index');
    }

    public function show(Request $request, $userId )
    {
        abort_unless(\Gate::allows('teachers_show'), 403);
        $teacher = \DB::table('users')
            ->join('teachers', function($join) use ($userId) {
                $join->on('users.id', '=', 'teachers.user_id');
                $join->where('users.id','=', $userId);
            })
            ->get(['users.id as userId','users.name as name', 'teachers.designation as designation', 'teachers.qualification as qualification', 'teachers.dob as dob', 'teachers.gender as gender', 'teachers.email as email', 'teachers.phone_no as phone_no', 'teachers.address as address', 'teachers.joining_date as joining_date']);

        $name = '';
        $designation = '';
        $qualification = '';
        $dob = '';
        $gender = '';
        $email = '';
        $phone_no = '';
        $address = '';
        $joining_date = '';
        foreach ($teacher as $key => $value) {
            $name = $value->name;
            $designation = $value->designation;
            $qualification = $value->qualification;
            $dob = $value->dob;
            $gender = self::GENDER[$value->gender];
            $email = $value->email;
            $phone_no = $value->phone_no;
            $address = $value->address;
            $joining_date = $value->joining_date;
        }
        return view('admin.teachers.show', compact('name', 'email', 'dob', 'designation', 'qualification', 'gender', 'email', 'phone_no', 'address', 'joining_date'));
    }

    public function destroy(Teacher $teacher)
    {
        abort_unless(\Gate::allows('teachers_delete'), 403);
        $teacher->delete();
        return back();
    }

    public function massDestroy(MassDestroyTeacherRequest $request)
    {
        Teacher::whereIn('id', request('ids'))->delete();
        return response(null, 204);
    }

    public function assign(User $user, $teacherId)
    {
        $allSchools = School::get();
        return view('admin.teachers.assign-school', compact('allSchools', 'teacherId'));
    }

    public function assignSchool(Request $request) {
        $inputData = $request->all();
        Teacher::where('user_id', $inputData['teacherId'])->update(['school_id' => $inputData['school_id']]);
        return redirect()->route('admin.teachers.index')->with('success', 'School assigned successfully');;;
    }
}
