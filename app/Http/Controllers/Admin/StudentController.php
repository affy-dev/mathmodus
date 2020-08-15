<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyStudentRequest;
use App\Http\Requests\StoreSchoolRequest;
use App\Http\Requests\UpdateSchoolRequest;
use App\Http\Requests\AssignPrincipalRequest;
use App\School;
use App\User;
use App\Student;
use App\Teacher;
use Illuminate\Http\Request;
use App\StudentTestResults;
use App\Courses;
use App\Questions;
use App\Lessons;

class StudentController extends Controller
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

    const STUDENT_ROLE = 3;

    const TEST_STATUS = [
        'PENDING' => 'pending',
        'COMPLETED' => 'completed'
    ];

    public function index()
    {
        abort_unless(\Gate::allows('student_access'), 403);
        $currentUserRoles = RolesController::getLoggedInUserRolesIdAndName();
        if($currentUserRoles->title == 'Principal') {
            $students = \DB::table('users')
            ->join('role_user', function($join) {
                $join->on('users.id', '=', 'role_user.user_id');
                $join->where('role_user.role_id', self::STUDENT_ROLE);
            })
            ->join('students', 'users.id', '=', 'students.user_id')
            ->join('schools', 'schools.id', '=', 'students.school_id')
            ->where('schools.principal_id', '=', $currentUserRoles->user_id)
            ->get(['users.id as userId', 'students.created_by as created_by_id', 'users.name as userName', 'schools.school_name as school_name', 'users.email as emailId', 'students.dob as studentDOB', 'students.father_name as fatherName', 'students.father_phone_no as fatherPhone', 'students.id as studentId']);
        } else if($currentUserRoles->title == 'Teacher') {
            $students = \DB::table('users')
            ->join('role_user', function($join) {
                $join->on('users.id', '=', 'role_user.user_id');
                $join->where('role_user.role_id', self::STUDENT_ROLE);
            })
            ->join('students', 'users.id', '=', 'students.user_id')
            ->join('schools', 'schools.id', '=', 'students.school_id')
            ->where('students.teacher_id', '=', $currentUserRoles->user_id)
            ->get(['users.id as userId', 'students.created_by as created_by_id', 'users.name as userName', 'schools.school_name as school_name', 'users.email as emailId', 'students.dob as studentDOB', 'students.father_name as fatherName', 'students.father_phone_no as fatherPhone', 'students.id as studentId']);
        } else {
            $students = \DB::table('users')
            ->join('role_user', function($join) {
                $join->on('users.id', '=', 'role_user.user_id');
                $join->where('role_user.role_id', self::STUDENT_ROLE);
            })
            ->join('students', 'users.id', '=', 'students.user_id')
            ->join('schools', 'schools.id', '=', 'students.school_id')
            ->get(['users.id as userId', 'students.created_by as created_by_id', 'users.name as userName', 'schools.school_name as school_name', 'users.email as emailId', 'students.dob as studentDOB', 'students.father_name as fatherName', 'students.father_phone_no as fatherPhone', 'students.id as studentId']);
        }
            
        
        return view('admin.students.index', compact('students'));
    }

    public function getUserNameById($userId) {
        $user = User::where('id', $userId)->first();
        return $user_name;
    }

    public function create()
    {
        abort_unless(\Gate::allows('student_create'), 403);
        $gender = self::GENDER;
        $blood_group = self::BLOOD_GROUP;
        $users = \DB::table('users')
            ->join('role_user', function($join) {
                $join->on('users.id', '=', 'role_user.user_id');
                $join->where('role_user.role_id', self::STUDENT_ROLE);
            })
            ->where('users.data_updated', 0)
            ->whereNull('users.deleted_at')
            ->get();
        
        $allSchools = School::get();
        $allTeacher = Teacher::get();
        return view('admin.students.create', compact('gender', 'blood_group', 'users', 'allSchools', 'allTeacher'));
    }

    public function store(Request $request)
    {
        abort_unless(\Gate::allows('student_create'), 403);
        $rules = [
            'user_id' => 'required|unique:students,user_id',
            'school_id' => 'required',
            'teacher_id' => 'required',
            'dob' => 'required|min:10|max:10',
            'gender' => 'required|integer',
            'blood_group' => 'nullable',
            'email' => 'email|max:255|unique:students,email',
            'phone_no' => 'max:10',
            'father_name' => 'required|max:255',
            'father_phone_no' => 'required|max:15',
            'mother_name' => 'required|max:255',
            'mother_phone_no' => 'max:15',
            'present_address' => 'required|max:500',
            'permanent_address' => 'required|max:500',
        ];

        $this->validate($request, $rules);
        $userName = User::find($request->input('user_id'));
        $studentsData = $request->all();
        $studentsData['name'] = $userName->name;
        $studentsData['created_by'] = auth()->user()->id;
        $students = Student::create($studentsData);
        User::where('id', $request->input('user_id'))->update(['data_updated'=>1]); // update that full details have been updated
        return redirect()->route('admin.students.index');
    }

    public function edit(Request $request, $userId)
    {
        abort_unless(\Gate::allows('student_edit'), 403);
        $student = \DB::table('users')
            ->join('students', function($join) use ($userId) {
                $join->on('users.id', '=', 'students.user_id');
                $join->where('users.id','=', $userId);
            })
            ->whereNull('users.deleted_at')
            ->get(['users.id as userId','students.id as studentId','users.name as userName', 'users.email as emailId', 'students.dob as studentDOB', 'students.father_name as fatherName', 'students.father_phone_no as fatherPhone', 'students.gender as studentGender', 'students.blood_group as studentBloodGroup', 'students.mother_name as studentMothenName', 'students.mother_phone_no as studentMotherPhoneNo', 'students.present_address', 'students.permanent_address', 'students.phone_no']);

        $userName = '';
        $emailId = '';
        $studentDOB = '';
        $fatherName = '';
        $fatherPhone = '';
        $studentGender = '';
        $studentBloodGroup = '';
        $studentMothenName = '';
        $studentMotherPhoneNo = '';
        $present_address = '';
        $permanent_address = '';
        $studentId = '';
        $studentPhoneNo = '';
        foreach ($student as $key => $value) {
            $userName = $value->userName;
            $emailId = $value->emailId;
            $studentDOB = $value->studentDOB;
            $fatherName = $value->fatherName;
            $fatherPhone = $value->fatherPhone;
            $studentGender = $value->studentGender;
            $studentBloodGroup = $value->studentBloodGroup;
            $studentMothenName = $value->studentMothenName;
            $studentMotherPhoneNo = $value->studentMotherPhoneNo;
            $present_address = $value->present_address;
            $permanent_address = $value->permanent_address;
            $studentId = $value->studentId;
            $studentPhoneNo = $value->phone_no;
        }

        $gender = self::GENDER;
        $blood_group = self::BLOOD_GROUP;
        return view('admin.students.edit', compact('userName', 'emailId', 'studentDOB', 'fatherName', 'fatherPhone', 'studentGender', 'studentBloodGroup', 'studentMothenName', 'studentMotherPhoneNo', 'present_address', 'permanent_address', 'gender', 'blood_group', 'studentId', 'studentPhoneNo', 'userId'));
    }

    public function update(Request $request, $studentId)
    {
        abort_unless(\Gate::allows('student_edit'), 403);
        
        $rules = [
            'name' => 'required|unique:users,id',
            'dob' => 'required|min:10|max:10',
            'gender' => 'required|integer',
            'blood_group' => 'nullable',
            'email' => 'email|max:255|unique:students,email',
            'phone_no' => 'max:10',
            'father_name' => 'required|max:255',
            'father_phone_no' => 'required|max:15',
            'mother_name' => 'required|max:255',
            'mother_phone_no' => 'max:15',
            'present_address' => 'required|max:500',
            'permanent_address' => 'required|max:500',
        ];
        $inputData = $request->except(['_token','_method']);
        $name = $inputData['name'];
        $email = $inputData['email'];
        $userId = $inputData['userId'];
        $this->validate($request, $rules);
        User::where('id', $userId)->update(['name'=>$name, 'email'=>$email]); // update USER table data
        unset($inputData['name']);
        unset($inputData['userId']);
        Student::where('id', $studentId)->update($inputData);
        return redirect()->route('admin.students.index');
    }

    public function show(Request $request, $userId )
    {
        abort_unless(\Gate::allows('student_show'), 403);
        $student = \DB::table('users')
            ->join('students', function($join) use ($userId) {
                $join->on('users.id', '=', 'students.user_id');
                $join->where('users.id','=', $userId);
            })
            ->get(['users.id as userId','users.name as userName', 'users.email as emailId', 'students.dob as studentDOB', 'students.father_name as fatherName', 'students.father_phone_no as fatherPhone', 'students.gender as studentGender', 'students.blood_group as studentBloodGroup', 'students.mother_name as studentMothenName', 'students.mother_phone_no as studentMotherPhoneNo', 'students.present_address', 'students.permanent_address', 'students.phone_no']);

        $userName = '';
        $emailId = '';
        $studentDOB = '';
        $fatherName = '';
        $fatherPhone = '';
        $studentGender = '';
        $studentBloodGroup = '';
        $studentMothenName = '';
        $studentMotherPhoneNo = '';
        $present_address = '';
        $permanent_address = '';
        $phone_no='';
        foreach ($student as $key => $value) {
            $userName = $value->userName;
            $emailId = $value->emailId;
            $studentDOB = $value->studentDOB;
            $fatherName = $value->fatherName;
            $fatherPhone = $value->fatherPhone;
            $studentGender = $value->studentGender ? self::GENDER[$value->studentGender] : 'N/A';
            $studentBloodGroup = $value->studentBloodGroup ? self::BLOOD_GROUP[$value->studentBloodGroup] : 'N/A';
            $studentMothenName = $value->studentMothenName;
            $studentMotherPhoneNo = $value->studentMotherPhoneNo;
            $present_address = $value->present_address;
            $permanent_address = $value->permanent_address;
            $phone_no = $value->phone_no;
        }
        
        $examsTaken = StudentTestResults::where('user_id', $userId)
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

        
        return view('admin.students.show', compact('userName', 'emailId', 'studentDOB', 'fatherName', 'fatherPhone', 'studentGender', 'studentBloodGroup', 'studentMothenName', 'studentMotherPhoneNo', 'present_address', 'permanent_address', 'phone_no', 'total_ans', 'correct_ans', 'wrong_ans', 'mapCorrIncorrWithCourse', 'correctAns', 'wrongAns', 'finalCorrectMapInfo', 'finalInCorrectMapInfo', 'IncorrectLessonsName', 'CorrectLessonsName', 'totalTestGiven', 'courseNames', 'examsTaken'));
    }

    public function destroy(Student $student)
    {
        abort_unless(\Gate::allows('student_delete'), 403);

        $student->delete();

        return back();
    }

    public function massDestroy(MassDestroyStudentRequest $request)
    {
        Student::whereIn('id', request('ids'))->delete();
        return response(null, 204);
    }

}