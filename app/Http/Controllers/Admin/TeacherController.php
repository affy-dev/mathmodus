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
use RealRashid\SweetAlert\Facades\Alert;
use App\StudentTestResults;
use App\Courses;
use App\Questions;
use App\Lessons;
use Illuminate\Support\Facades\Hash;

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
    const PRINCIPAL_ROLE = 2;

    const TEST_STATUS = [
        'PENDING' => 'pending',
        'COMPLETED' => 'completed'
    ];

    public function index()
    {
        abort_unless(\Gate::allows('teachers_access'), 403);

        $currentUserRoles = RolesController::getLoggedInUserRolesIdAndName();
        if($currentUserRoles->roleId == self::PRINCIPAL_ROLE) {
            $teachers = \DB::table('users')
            ->join('role_user', function($join) {
                $join->on('users.id', '=', 'role_user.user_id');
                $join->where('role_user.role_id', self::TEACHER_ROLE);
            })
            ->join('teachers', 'users.id', '=', 'teachers.user_id')
            ->join('schools', 'schools.id', '=', 'teachers.school_id')
            ->where('schools.principal_id', '=', $currentUserRoles->user_id)
            ->get(['users.id as userId', 'schools.school_name as school_name', 'users.name as name', 'users.email as email', 'teachers.dob as dob', 'teachers.designation as designation', 'teachers.id as teacherId', 'teachers.phone_no as phone_no']);
        } else if($currentUserRoles->roleId == self::TEACHER_ROLE) {
            $teachers = \DB::table('users')
            ->join('role_user', function($join) {
                $join->on('users.id', '=', 'role_user.user_id');
                $join->where('role_user.role_id', self::TEACHER_ROLE);
            })
            ->join('teachers', 'users.id', '=', 'teachers.user_id')
            ->join('schools', 'schools.id', '=', 'teachers.school_id')
            ->where('students.teacher_id', '=', $currentUserRoles->user_id)
            ->get(['users.id as userId', 'schools.school_name as school_name', 'users.name as name', 'users.email as email', 'teachers.dob as dob', 'teachers.designation as designation', 'teachers.id as teacherId', 'teachers.phone_no as phone_no']);
        } else {
            $teachers = \DB::table('users')
                ->join('role_user', function($join) {
                    $join->on('users.id', '=', 'role_user.user_id');
                    $join->where('role_user.role_id', self::TEACHER_ROLE);
                })
                ->join('teachers', 'users.id', '=', 'teachers.user_id')
                ->leftJoin('schools', 'schools.id', '=', 'teachers.school_id')
                ->get(['users.id as userId', 'schools.school_name as school_name', 'users.name as name', 'users.email as email', 'teachers.dob as dob', 'teachers.designation as designation', 'teachers.id as teacherId', 'teachers.phone_no as phone_no']);
        }

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
            // ->where('users.data_updated', 0)
            ->whereNull('users.deleted_at')
            ->get();
        
        $allSchools = School::get();
        return view('admin.teachers.create', compact('gender', 'blood_group', 'users', 'allSchools'));
    }

    public function store(Request $request)
    {
        abort_unless(\Gate::allows('teachers_create'), 403);
        $rules = [
            // 'user_id' => 'required|unique:teachers,user_id',
            // 'school_id' => 'required',
            'name'              => 'required',
            'username'          => 'required|unique:users',
            'email'             => 'email|max:255|unique:teachers,email',
            'password'          => 'required',
            'dob'               => 'required|min:10|max:10',
            'gender'            => 'required|integer',
            'phone_no'          => 'required|max:10',
            'address'           => 'required|max:255',
        ];

        $this->validate($request, $rules);
        $inputData = $request->all();
        $hashedPassword = Hash::make($inputData['password']);
        $inputData['password'] = $hashedPassword;
        $teachersData = $inputData;

        unset($inputData['dob']);
        unset($inputData['gender']);
        unset($inputData['phone_no']);
        unset($inputData['address']);
        unset($inputData['designation']);
        unset($inputData['qualification']);
        unset($inputData['joining_date']);
        
        $inputData['created_by'] = auth()->user()->id;
        $inputData['user_status'] = 1;
        $user = User::create($inputData);
        $user->roles()->sync(self::TEACHER_ROLE);

        unset($teachersData['username']);
        unset($teachersData['email']);
        unset($teachersData['password']);

        $schoolId = School::where('principal_id', auth()->user()->id)->first();
        $teachersData['school_id'] = $schoolId->id;
        $teachersData['user_id'] = $user->id;
        $teachersData['created_by'] = auth()->user()->id;
        $teachersData['email'] = $inputData['email'];
        $teachersData['role_id'] = self::TEACHER_ROLE;
        $students = Teacher::create($teachersData);

        // $userName = User::find($request->input('user_id'));
        // $teacherData = $request->all();
        // $teacherData['name'] = $userName->name;
        // $teacherData['created_by'] = auth()->user()->id;
        // Teacher::create($teacherData);
        // User::where('id', $request->input('user_id'))->update(['data_updated'=>1]); // update that full details have been updated
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
            ->get(['users.id as userId', 'teachers.id as teacherId', 'users.name as name','users.username as userName', 'teachers.designation as designation', 'teachers.qualification as qualification', 'teachers.dob as dob', 'teachers.gender as gender', 'teachers.email as email', 'teachers.phone_no as phone_no', 'teachers.address as address', 'teachers.joining_date as joining_date', 'teachers.school_id as school_id']);

        foreach ($teacher as $key => $value) {
            $name = $value->name ? $value->name : '';
            $userName = $value->userName ? $value->userName : '';
            $designation = $value->designation ? $value->designation : '';
            $qualification = $value->qualification ? $value->qualification : '';
            $dob = $value->dob ? $value->dob : '';
            $genderValue = $value->gender ? $value->gender : '';
            $email = $value->email ? $value->email : '';
            $phone_no = $value->phone_no ? $value->phone_no : '';
            $address = $value->address ? $value->address : '';
            $joining_date = $value->joining_date ? $value->joining_date : '';
            $teacherId = $value->teacherId ? $value->teacherId : '';
            $schoolId = $value->school_id ? $value->school_id : '';
        }

        $gender = self::GENDER;
        $blood_group = self::BLOOD_GROUP;
        $allSchools = School::get();
        return view('admin.teachers.edit', compact('name', 'email', 'dob', 'designation', 'qualification', 'genderValue', 'email', 'phone_no', 'address', 'joining_date', 'teacherId', 'userId', 'gender', 'allSchools', 'schoolId', 'userName'));
    }

    public function update(Request $request, $teacherId)
    {
        abort_unless(\Gate::allows('teachers_edit'), 403);
        $inputData = $request->except(['_token','_method']);
        $name = $inputData['name'];
        $email = $inputData['email'];
        $userId = $inputData['userId'];
        
        $rules = [
            'name'              => 'required|unique:users,name,'.$userId,
            'username'          => 'required|unique:users,username,'.$userId,
            'email'             => 'required|email|unique:users,email,'.$userId,
            'dob'               => 'required|min:10|max:10',
            'gender'            => 'required|integer',
            'phone_no'          => 'required|max:10',
            'address'           => 'required|max:255',
            // 'school_id'         => 'required',
        ];

        $this->validate($request, $rules);
        $teachersData = $inputData;
        unset($inputData['dob']);
        unset($inputData['gender']);
        unset($inputData['phone_no']);
        unset($inputData['address']);
        unset($inputData['designation']);
        unset($inputData['qualification']);
        unset($inputData['joining_date']);
        unset($inputData['userId']);
        if(is_null($inputData['password'])) {
            unset($inputData['password']);
        } else {
            $hashedPassword = Hash::make($inputData['password']);
            $inputData['password'] = $hashedPassword;
        }
        
        User::where('id', $userId)->update($inputData); // update USER table data

        unset($teachersData['username']);
        unset($teachersData['email']);
        unset($teachersData['userId']);
        if (is_null($teachersData['password']) || !empty($teachersData['password'])) {
            unset($teachersData['password']);
        }
        Teacher::where('id', $teacherId)->update($teachersData);
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

        $getAllStudents = Student::where('teacher_id', $userId)->get()->toArray();


        $studentsId = [];
        foreach ($getAllStudents as $studentData) {
            $studentsId[] = $studentData['user_id'];
        }
        
        $examsTaken = StudentTestResults::whereIn('user_id', $studentsId)
        ->where('test_status', self::TEST_STATUS['COMPLETED'])
        ->get();
        
        $total_ans = 0;
        $correct_ans = 0;
        $wrong_ans = 0;

        if(count($examsTaken) > 0) {
            foreach ($examsTaken as $key => $value) {
                $total_ans = $total_ans + $value->total_ans;
                $correct_ans = $correct_ans + $value->correct_ans;
                $wrong_ans = $wrong_ans + $value->wrong_ans;
            }
        }
        $totalTestGiven = count($examsTaken);
        $courses = Courses::all();
        $availableCourses = [];
        foreach ($courses->toArray() as $key => $value) {
            $availableCourses[$value['id']] = $value['course_name'];
        }

        $correctAns = [];
        $wrongAns = [];
        $correctAnsIds = [];
        $wrongAnsIds = [];
        $mapCorrIncorrWithCourse = [];
        $counter = 0;
        $mapCounter = 0;
        $resultCount = [];
        $finalCorrectLessons = [];
        $lessonsCorrect = [];
        $lessonsWrong = [];
        $lessonsFinalData = [];
        
        foreach($examsTaken as $exms) {
            $correctAns[] = $exms->correct_ans;
            $wrongAns[] = $exms->wrong_ans;
            $resultCount[$exms->courseId]['correct'][] = $exms->correct_ans;
            $resultCount[$exms->courseId]['wrong'][] = $exms->wrong_ans;
        }
        $mapDataWithLessons = [];
        $c=0;
        $IncorrectLessonsName = [];
        $CorrectLessonsName = [];
        foreach($lessonsFinalData as $dt) {
            if($c==0) {
                $lessonsDetails = Lessons::whereIn('id', array_keys($dt))->get();
                foreach ($lessonsDetails as $less) {
                    $CorrectLessonsName[] = $less->lesson_name;
                }
                $mapDataWithLessons[$c]['name'] = 'Correct';
                $mapDataWithLessons[$c]['data'] = array_values($dt);  
            } else {
                $lessonsDetails = Lessons::whereIn('id', array_keys($dt))->get();
                foreach ($lessonsDetails as $less) {
                    $IncorrectLessonsName[] = $less->lesson_name;
                }
                $mapDataWithLessons[$c]['name'] = 'InCorrect';
                $mapDataWithLessons[$c]['data'] = array_values($dt);  
            }
            $c++;
        }

        $finalCorrectMapInfo = [];
        $finalInCorrectMapInfo = [];
        if(count($mapDataWithLessons) > 0) {
            $finalCorrectMapInfo[] = $mapDataWithLessons[0];
            $finalInCorrectMapInfo[] = $mapDataWithLessons[1];
        }
        
        
        //===================== Graph by Courses ========================
        $correctArr = [];
        $wrongArr = [];
        $courseNames = [];
        foreach($resultCount as $courseId => $ans) {
            $courseNames[] = $availableCourses[$courseId];
            $correctArr[] = array_sum($ans['correct']);
            $wrongArr[] = array_sum($ans['wrong']);
            if($counter == (count($resultCount)-1)) { 
                $mapCorrIncorrWithCourse[$mapCounter]['name'] = 'Correct';
                $mapCorrIncorrWithCourse[$mapCounter]['data'] = $correctArr;
                $mapCounter++;
                $mapCorrIncorrWithCourse[$mapCounter]['name'] = 'Incorrect';
                $mapCorrIncorrWithCourse[$mapCounter]['data'] = $wrongArr;
            }
            $counter++;
        }
        
        $mapCorrIncorrWithCourse = json_encode($mapCorrIncorrWithCourse);
        //===================== End Graph by Courses ========================

        return view('admin.teachers.show', compact('name', 'email', 'dob', 'designation', 'qualification', 'gender', 'email', 'phone_no', 'address', 'joining_date', 'total_ans', 'correct_ans', 'wrong_ans', 'mapCorrIncorrWithCourse', 'correctAns', 'wrongAns', 'finalCorrectMapInfo', 'finalInCorrectMapInfo', 'IncorrectLessonsName', 'CorrectLessonsName', 'totalTestGiven', 'courseNames', 'examsTaken', 'getAllStudents'));
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
        if(empty($inputData['school_id'])) {
            Alert::error('Please select school first!', '');
            return redirect()->route('admin.teachers.assign-school', $inputData['teacherId']);
        } else if( Teacher::where('school_id', '=', $inputData['school_id'])->exists() ) {
            Alert::error('Selected teacher is already assigned to this school', '');
            return redirect()->route('admin.teachers.assign-school', $inputData['teacherId']);
        }
        Teacher::where('user_id', $inputData['teacherId'])->update(['school_id' => $inputData['school_id']]);
        Alert::success('School assigned successfully', '');
        return redirect()->route('admin.teachers.index');
    }
}