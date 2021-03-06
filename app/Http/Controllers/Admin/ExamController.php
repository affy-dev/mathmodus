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
use App\Courses;
use App\Lessons;
use App\Questions;
use App\Topics;
use App\TopicPreRequisite;
use App\StudentTestResults;
use Illuminate\Http\Request;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use App\LessonPreRequisite;

class ExamController extends Controller
{
    private const QUESTION_COUNT = 10;
    private const QUESTION_PER_LESSONS = 25;

    const TEST_STATUS = [
        'PENDING' => 'pending',
        'COMPLETED' => 'completed'
    ];

    public function index()
    {
        abort_unless(\Gate::allows('exams_list'), 403);
        \Cache::forget('courseId');
        \Cache::forget('mcqs');
        \Cache::forget('testId');
        $courses = Courses::all();
        return view('admin.exam.index', compact('courses'));
    }

    public function takeExam(Request $request, $courseId, $lessonId = null, $testId = null) {
        abort_unless(\Gate::allows('take_exam'), 403);
        $expiresAt = Carbon::now()->addMinutes(180);
        $testName = '';
        $testFromLessonsTab = $lessonId != null ? true : 0;
        $courseDetails = !$testFromLessonsTab || $testId ? Courses::where('id', $courseId)->first() : Lessons::where('id', $lessonId)->first();
        $testName = !$testFromLessonsTab || $testId ? $courseDetails->course_name : $courseDetails->lesson_name;
        $imgSrcPath = !$testFromLessonsTab ? '../../../' : '../../../../../';
        
        if($testId != null) {
            $testDetails = StudentTestResults::where('id', $testId)->first();
            $getMcqs = unserialize($testDetails->mcqs);
            session(['testId' => $testId, 'testFromLessonsTab' => $testFromLessonsTab]);
            if( $testDetails->lessonId != 0 ) {
                $testName = Lessons::where('id', $testDetails->lessonId)->first()->lesson_name;
            }
            \Cache::put('courseId', $courseId, $expiresAt);
            \Cache::put('mcqs', $getMcqs, $expiresAt);
            \Cache::put('testId', $testId, $expiresAt);
        }
        if(!\Cache::has('courseId') && !\Cache::has('mcqs')) {
            if(!$testFromLessonsTab) {
                $lessons = Lessons::where('course_id', $courseId)->pluck('id')->toArray();
            } else {
                $lessons =[$lessonId];
            }
            $mcqs = [];
            $questionCount = 0;
            foreach ($lessons as $key => $lessionId) {
                $questionsLimit = $testFromLessonsTab ? self::QUESTION_COUNT : 1;
                $quest = Questions::where('lesson_id', $lessionId)->inRandomOrder()->limit($questionsLimit)->get()->toArray();
                $questionAnswerDetails = [];
                $i=0;
                $questionCount++;
                $countFromLessonTab=0;
                foreach ($quest as $key => $value) {
                    $countFromLessonTab++;
                    $answerOptions = \DB::table('question_answer')
                    ->where('question_id', $value['id'])
                    ->get();
                    $ansDet = [];
                    foreach ($answerOptions as $ans) {
                        $ansDet[] = $ans;
                    }
                    $questionAnswerDetails[$i]['questionDetails'] = $value;
                    $questionAnswerDetails[$i]['answerDetails'] = $ansDet;
                    $questionAnswerDetails[$i]['questNumber'] = $testFromLessonsTab ? $countFromLessonTab : $questionCount;
                    $i++;
                }
                $mcqs[] = $questionAnswerDetails;
            }
            // $checkIfQuestionsAreEmpty = 0;
            // foreach ($mcqs as $key => $value) {
            //     if(count($value) != 0) {
            //         $checkIfQuestionsAreEmpty++;
            //     }
            // }
            $testId=0;
            // if( $checkIfQuestionsAreEmpty != 0 ) {
                $testCreated = StudentTestResults::create([
                    'user_id'       =>  auth()->user()->id, 
                    'courseId'      =>  $courseId,
                    'test_status'   =>  self::TEST_STATUS['PENDING'],
                    'mcqs'          =>  serialize($mcqs),
                    'lessonId'      =>  $lessonId != null ? $lessonId : 0
                ]);
                $testId = $testCreated->id;
                session(['testId' => $testId, 'testFromLessonsTab' => $testFromLessonsTab]);
                \Cache::put('courseId', $courseId, $expiresAt);
                \Cache::put('mcqs', $mcqs, $expiresAt);
                \Cache::put('testId', $testId, $expiresAt);
            // }
        } else {
            $courseId = \Cache::get('courseId');
            $mcqs = \Cache::get('mcqs');
            $testId = \Cache::get('testId');
        }
        if(!StudentTestResults::where('id', $testId)->exists()) {
            \Cache::forget('courseId');
            \Cache::forget('mcqs');
            \Cache::forget('testId');
            session()->forget('testId');
            session()->forget('testFromLessonsTab');
            return redirect()->route('admin.exams.index')->withErrors(['There are no questions available in this test']);
        }
        return view('admin.exam.take-exam', compact('courseId', 'mcqs', 'testId', 'testName', 'imgSrcPath', 'testFromLessonsTab'));
    }

    public function submitExam(Request $request) {
        abort_unless(\Gate::allows('submit_exam'), 403);
        $data = $request->all();
        if(!StudentTestResults::where('id', $data['testId'])->exists() || StudentTestResults::where('id', $data['testId'])->where('test_status', self::TEST_STATUS['COMPLETED'])->exists()) {
            return redirect()->route('admin.exams.index')->withErrors(['Last Test had been alreadt completed or skipped by you. Start with another test']);
        }
        
        $courseId = $data['courseId'];
        $allQuestionIds = $data['questionIds'];

        // validation ends here
        $correctAnsIds = [];
        $wrongAnsIds = [];
        $counter=0;
        $wrong_lesson_ids = [];
        $correct_lesson_ids = [];
        foreach ($allQuestionIds as $quesId) {
            $counter++;
            $ansId = array_key_exists('answerGroup_'.$quesId, $data) ? $data['answerGroup_'.$quesId] : $quesId;
            $questNumber = $data['questNumber_'.$quesId];
            if(array_key_exists('answerGroup_'.$quesId, $data)) { // answered list comers here
                $ansId = $data['answerGroup_'.$quesId];
                $ansDetails = \DB::table('question_answer')
                ->where('id', $ansId)
                ->first();
                
                $questionDetails = \DB::table('questions')
                                    ->where('id', $ansDetails->question_id)
                                    ->first();
                $ansDetails->quesNum = $questNumber;
                $ansDetails->lesson_id = $questionDetails->lesson_id;
                $ansDetails->question_text = $questionDetails->question_text;
                if($ansDetails->correct_answer == 'TRUE') {
                    $correctAnsIds[] = $ansDetails;
                    $correct_lesson_ids[] = $questionDetails->lesson_id;
                } else {
                    $wrongAnsIds[] = $ansDetails;
                    $wrong_lesson_ids[] = $questionDetails->lesson_id;
                }
            } else { // unanswered comes here
                $ansDetails = \DB::table('question_answer')
                ->where('question_id', $quesId)
                ->first();
                $questionDetails = \DB::table('questions')
                                    ->where('id', $ansDetails->question_id)
                                    ->first();
                $ansDetails->quesNum = $questNumber;
                $ansDetails->lesson_id = $questionDetails->lesson_id;
                $ansDetails->question_text = $questionDetails->question_text;
                $wrongAnsIds[] = $ansDetails;
                $wrong_lesson_ids[] = $questionDetails->lesson_id;
            }
        }

        $correctQuestionCount = count($correctAnsIds);
        $wrongQuestionCount = count($wrongAnsIds);
        $totalQuestionCount = $correctQuestionCount + $wrongQuestionCount;

        StudentTestResults::where('id', session('testId'))->update([
            'user_id'=> auth()->user()->id, 
            'courseId' =>$courseId,
            'correctAnsIds' => serialize($correctAnsIds),
            'wrongAnsIds' => serialize($wrongAnsIds),
            'test_status' => self::TEST_STATUS['COMPLETED'],
            'total_ans' => $totalQuestionCount,
            'correct_ans' => $correctQuestionCount,
            'wrong_ans' => $wrongQuestionCount,
            'testFromLessonsTab' => session('testFromLessonsTab'),
            'wrong_lesson_ids' => serialize($wrong_lesson_ids),
            'correct_lesson_ids' => serialize($correct_lesson_ids),
        ]);
        \Cache::forget('courseId');
        \Cache::forget('mcqs');
        \Cache::forget('testId');

        session(['correctAnsIds' => $correctAnsIds, 'wrongAnsIds' => $wrongAnsIds, 'test_status' => self::TEST_STATUS['COMPLETED']]);
        return redirect()->route('admin.exams.examresult');
    }

    public function examResults(Request $request, $testId = null) {
        abort_unless(\Gate::allows('exam_results'), 403);
        if($testId == null && !session()->has('correctAnsIds') && !session()->has('wrongAnsIds') && !session()->has('test_status')) {
            return redirect()->route('admin.exams.history');
        }
        $getLessonName = '';
        $getTestAnalysis = $this->getTestAnalysis($testId);
        $correctQuestionDetails = $getTestAnalysis['correctQuestionDetails'];
        $wrongQuestionDetails = $getTestAnalysis['wrongQuestionDetails'];
        $testFromLessonsTab = $getTestAnalysis['testFromLessonsTab'];
        $correct_lesson_ids = $getTestAnalysis['correct_lesson_ids'];
        $wrong_lesson_ids = $getTestAnalysis['wrong_lesson_ids'];
        if($testId == null) {
            session()->forget('correctAnsIds');
            session()->forget('wrongAnsIds');
            session()->forget('test_status');
            session()->forget('testId');
            session()->forget('testFromLessonsTab');
        }
        $showBackBtn = true;
        if((strpos(url()->previous(),'take-exam') !== false)) {
            $showBackBtn = false;
        }
        if($testFromLessonsTab) {
            if(count($getTestAnalysis['correctQuestionDetails']) > 0) {
                $getLessonName = Lessons::where('id', $getTestAnalysis['correctQuestionDetails'][0]['lesson_id'])->get(['lesson_name','id']);
            } else {
                $getLessonName = Lessons::where('id', $getTestAnalysis['wrongQuestionDetails'][0]['lesson_id'])->get(['lesson_name','id']);
            }
            
        }
        
        return view('admin.exam.exam-result', compact('correctQuestionDetails', 'wrongQuestionDetails', 'showBackBtn', 'testFromLessonsTab', 'getLessonName'));
    }

    private function getTestAnalysis($testId = null) {
        $correctAnsIds = '';
        $wrongAnsIds = '';
        $test_status = '';
        $wrong_lesson_ids = [];
        $correct_lesson_ids = [];
        if( $testId != null ) {
            $testHistory = StudentTestResults::where('user_id', auth()->user()->id)
                                            ->where('test_status', self::TEST_STATUS['COMPLETED'])
                                            ->where('id', $testId)
                                            ->get();
                                            // dd($testHistory);
            $correctAnsIds = unserialize($testHistory[0]['correctAnsIds']);
            $wrongAnsIds   = unserialize($testHistory[0]['wrongAnsIds']);
            $wrong_lesson_ids   = !empty($testHistory[0]['wrong_lesson_ids']) ? unserialize($testHistory[0]['wrong_lesson_ids']) : [];
            $correct_lesson_ids   = !empty($testHistory[0]['correct_lesson_ids']) ? unserialize($testHistory[0]['correct_lesson_ids']) : [];
            $test_status   = $testHistory[0]['test_status'];
            $testFromLessonsTab   = $testHistory[0]['testFromLessonsTab'];
        } else {
            $correctAnsIds = session('correctAnsIds');
            $wrongAnsIds = session('wrongAnsIds');
            $test_status = session('test_status');
            $testFromLessonsTab = session('testFromLessonsTab');
        }
        $correctQuestionDetails = [];
        $wrongQuestionDetails = [];
        foreach($correctAnsIds as $correctQuestions) {
            $questDetails = Questions::where('id', $correctQuestions->question_id)->get()->toArray();
            $lessonVideo = Topics::where('lession_id', $questDetails[0]['lesson_id'])->get()->toArray();
            $fullLessonVideo = Lessons::where('id', $questDetails[0]['lesson_id'])->get()->toArray();
            $questDetails[0]['full_video_url'] = (count($fullLessonVideo) != 0) ? $fullLessonVideo['0']['video_url'] : 'not_available';
            $questDetails[0]['video_url'] = (count($lessonVideo) != 0) ? $lessonVideo['0']['video_url'] : 'not_available';
            $questDetails[0]['misc_urls'] = (count($fullLessonVideo) != 0) ? $fullLessonVideo['0']['misc_urls'] : 'not_available';
            $questDetails[0]['courseId'] = $fullLessonVideo['0']['course_id'];
            $questDetails[0]['quesNum'] = $correctQuestions->quesNum;
            // all the prerequisite topics are found here
            $topicPreRequisite = (count($fullLessonVideo) != 0) ? LessonPreRequisite::where('lession_id', $questDetails[0]['lesson_id'])->get()->toArray() : [];
            $preRequisiteIds = '';
            $topicPreRequisiteArray = [];
            foreach($topicPreRequisite as $preRequisite) {
                $topicName = Lessons::where('id', $preRequisite['lessons_pre_requisite_id'])->get()->toArray();
                if(!$topicName)
                    continue;

                $topicPreRequisiteArray[$topicName[0]['lesson_name']] = $topicName[0]['video_url'];
            }
            $questDetails[0]['topic_pre_requisite'] = $topicPreRequisiteArray;
            $correctQuestionDetails[] = $questDetails[0];
        }
        foreach($wrongAnsIds as $wrongQuestions) {
            $questDetails = Questions::where('id', $wrongQuestions->question_id)->get()->toArray();
            $lessonVideo = Topics::where('lession_id', $questDetails[0]['lesson_id'])->get()->toArray();
            $fullLessonVideo = Lessons::where('id', $questDetails[0]['lesson_id'])->get()->toArray();
            $questDetails[0]['full_video_url'] = (count($fullLessonVideo) != 0) ? $fullLessonVideo['0']['video_url'] : 'not_available';
            $questDetails[0]['video_url'] = (count($lessonVideo) != 0) ? $lessonVideo['0']['video_url'] : 'not_available';
            $questDetails[0]['misc_urls'] = (count($fullLessonVideo) != 0) ? $fullLessonVideo['0']['misc_urls'] : 'not_available';
            $questDetails[0]['courseId'] = $fullLessonVideo['0']['course_id'];
            $questDetails[0]['quesNum'] = $wrongQuestions->quesNum;
            // dd($questDetails);
            // all the prerequisite topics are found here
            $topicPreRequisite = (count($fullLessonVideo) != 0) ? LessonPreRequisite::where('lession_id', $questDetails[0]['lesson_id'])->get()->toArray() : [];
            $preRequisiteIds = '';
            $topicPreRequisiteArray = [];
            foreach($topicPreRequisite as $preRequisite) {
                $topicName = Lessons::where('id', $preRequisite['lessons_pre_requisite_id'])->get()->toArray();
                if(!$topicName)
                    continue;

                $topicPreRequisiteArray[$topicName[0]['lesson_name']] = $topicName[0]['video_url'];
            }
            // dd($topicPreRequisiteArray);
            $questDetails[0]['topic_pre_requisite'] = $topicPreRequisiteArray;
            $wrongQuestionDetails[] = $questDetails[0];
        }
        
        return [
            'correctQuestionDetails' => $correctQuestionDetails,
            'wrongQuestionDetails' => $wrongQuestionDetails,
            'testFromLessonsTab' => $testFromLessonsTab,
            'correct_lesson_ids' => $correct_lesson_ids,
            'wrong_lesson_ids' => $wrong_lesson_ids,
        ];
    }

    public function getHistory() {
        abort_unless(\Gate::allows('exam_history'), 403);
        $testHistory = StudentTestResults::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->paginate(10);
        // dd($testHistory);
        $courses = Courses::get(['id', 'course_name'])->toArray();
        $availableCourses = [];
        foreach ($courses as $key => $value) {
            $availableCourses[$value['id']] = $value['course_name'];
        }
        $lessons = Lessons::get(['id', 'lesson_name'])->toArray();
        $availableLessons = [];
        foreach ($lessons as $key => $lessonDetails) {
            $availableLessons[$lessonDetails['id']] = $lessonDetails['lesson_name'];
        }
        // dd($availableLessons);
        $canDeleteTest = auth()->user()->can_delete_test;
        return view('admin.exam.exam-history', compact('testHistory', 'availableCourses', 'canDeleteTest', 'availableLessons'));
    }

    public function deleteTest(Request $request, $testId) {
        StudentTestResults::where('id', $testId)->delete();
        Alert::success('Test Deleted Successfully', '');
        return redirect()->route('admin.exams.history');
    }

    public function lessonVideos(Request $request, $courseId, $testId = null) {
        abort_unless(\Gate::allows('exam_lesson_videos'), 403);
        \Cache::forget('courseId');
        \Cache::forget('mcqs');
        \Cache::forget('testId');
        if($testId != null && StudentTestResults::where('id', $testId)->exists()) {
            StudentTestResults::where('id', $testId)->delete();
        }
        $lessonVideos = $this->getAllTopicsVideosByCourseId($courseId);
        $showBackBtn = true;
        if((strpos(url()->previous(),'take-exam') !== false)) {
            $showBackBtn = false;
        }
        $courseName = Courses::where('id', $courseId)->first();
        return view('admin.exam.lesson-videos', compact('lessonVideos', 'showBackBtn', 'courseId', 'courseName'));
    }

    public function reports(Request $request) {
        $lessons = Lessons::all(['lesson_name','id'])->toArray();
        $allLessons = [];
        foreach($lessons as $less) {
            $allLessons[$less['id']] = $less['lesson_name'];
        }
        // dd($allLessons);
        return view('admin.exam.reports', compact('allLessons'));
    }

    private function getAllTopicsVideosByCourseId($courseId) {
        if($courseId) {
            return Lessons::where('course_id', $courseId)->paginate(15);
        }
        return [];
    }

    

}
