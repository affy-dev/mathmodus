<?php

namespace App\Http\Controllers\Admin;
use App\User;
use App\School;
use App\StudentTestResults;
use App\Courses;
use App\Questions;
use App\Lessons;

class HomeController
{
    const TEST_STATUS = [
        'PENDING' => 'pending',
        'COMPLETED' => 'completed'
    ];

    public function index()
    {
        abort_unless(\Gate::allows('access_dashboard'), 403);
        $countPrincipal = User::whereHas('roles', function($q){$q->whereIn('id', [env('USER_ROLES_PRINCIPAL', '2')]);})->count();
        $countStudent = User::whereHas('roles', function($q){$q->whereIn('id', [env('USER_ROLES_STUDENTS', '3')]);})->count();
        $countTeacher = User::whereHas('roles', function($q){$q->whereIn('id', [env('USER_ROLES_TEACHER', '4')]);})->count();
        $schoolCount = School::count();
        $countUser = User::count();
        $examsTaken = StudentTestResults::where('user_id', auth()->user()->id)->where('test_status', self::TEST_STATUS['COMPLETED'])->get();
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
            
            foreach(unserialize($exms->correctAnsIds) as $correcData) {
                $questionDetails = Questions::where('id', $correcData->question_id)->first();
                $lessonDetails = Lessons::where('id', $questionDetails->lesson_id)->first();
                $lessonsFinalData['correct'][$lessonDetails->id] = (isset($lessonsFinalData['correct'][$lessonDetails->id])) ? $lessonsFinalData['correct'][$lessonDetails->id]+1 : 1;
            }

            foreach(unserialize($exms->wrongAnsIds) as $wrongData) {
                $questionDetails = Questions::where('id', $wrongData->question_id)->first();
                $lessonDetails = Lessons::where('id', $questionDetails->lesson_id)->first();
                $lessonsFinalData['wrong'][$lessonDetails->id] = (isset($lessonsFinalData['wrong'][$lessonDetails->id])) ? $lessonsFinalData['wrong'][$lessonDetails->id]+1 : 1;
            }
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
        // dd($mapCorrIncorrWithCourse);
        $mapCorrIncorrWithCourse = json_encode($mapCorrIncorrWithCourse);

        //===================== Graph by Lessons ========================
        // dd($correctAnsIds);

        
        return view('home', compact('mapCorrIncorrWithCourse', 'availableCourses', 'courseNames', 'countPrincipal', 'countStudent', 'countTeacher', 'schoolCount', 'countUser', 'totalTestGiven', 'correctAns', 'wrongAns', 'finalCorrectMapInfo', 'finalInCorrectMapInfo', 'IncorrectLessonsName', 'CorrectLessonsName'));
    }
}
