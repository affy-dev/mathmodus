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

class ExamController extends Controller
{
    private const QUESTION_COUNT = 15;

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

    public function takeExam(Request $request, $courseId, $lessonId = null) {
        abort_unless(\Gate::allows('take_exam'), 403);
        $testName = '';
        $testFromLessonsTab = $lessonId != null ? true : false;
        $courseDetails = !$testFromLessonsTab ? Courses::where('id', $courseId)->first() : Lessons::where('id', $lessonId)->first();
        $testName = !$testFromLessonsTab ? $courseDetails->course_name : $courseDetails->lesson_name;
        if(!\Cache::has('courseId') && !\Cache::has('mcqs')) {
            if(!$testFromLessonsTab) {
                $lessons = Lessons::where('course_id', $courseId)->pluck('id')->random(self::QUESTION_COUNT)->toArray();
            } else {
                $lessons =[$lessonId];
            }
            $mcqs = [];
            foreach ($lessons as $key => $lessionId) {
                $questionsLimit = $testFromLessonsTab ? self::QUESTION_COUNT : 1;
                $quest = Questions::where('lesson_id', $lessionId)->inRandomOrder()->limit($questionsLimit)->get()->toArray();
                
                $questionAnswerDetails = [];
                $i=0;
                foreach ($quest as $key => $value) {
                    $answerOptions = \DB::table('question_answer')
                    ->where('question_id', $value['id'])
                    ->get();
                    $ansDet = [];
                    foreach ($answerOptions as $ans) {
                        $ansDet[] = $ans;
                    }
                    $questionAnswerDetails[$i]['questionDetails'] = $value;
                    $questionAnswerDetails[$i]['answerDetails'] = $ansDet;
                    $i++;
                }
                $mcqs[] = $questionAnswerDetails;
            }
            
            $checkIfQuestionsAreEmpty = 0;
            foreach ($mcqs as $key => $value) {
                if(count($value) != 0) {
                    $checkIfQuestionsAreEmpty++;
                }
            }
            $testId=0;
            if( $checkIfQuestionsAreEmpty != 0 ) {
                $testCreated = StudentTestResults::create([
                    'user_id'=> auth()->user()->id, 
                    'courseId' => $courseId,
                    'test_status' => self::TEST_STATUS['PENDING']
                ]);

                $testId = $testCreated->id;
                session(['testId' => $testId]);
                
                $expiresAt = Carbon::now()->addMinutes(30);
                \Cache::put('courseId', $courseId, $expiresAt);
                \Cache::put('mcqs', $mcqs, $expiresAt);
                \Cache::put('testId', $testId, $expiresAt);
            }
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
            return redirect()->route('admin.exams.index')->withErrors(['There are no questions available in this test']);
        }
        
        return view('admin.exam.take-exam', compact('courseId', 'mcqs', 'testId', 'testName'));
    }

    public function submitExam(Request $request) {
        abort_unless(\Gate::allows('submit_exam'), 403);
        $data = $request->all();
        
        if(!StudentTestResults::where('id', $data['testId'])->exists() || StudentTestResults::where('id', $data['testId'])->where('test_status', self::TEST_STATUS['COMPLETED'])->exists()) {
            return redirect()->route('admin.exams.index')->withErrors(['Last Test had been alreadt completed or skipped by you. Start with another test']);
        }
        
        $courseId = $data['courseId'];
        // $answerIds = $data['answerId'];
        $allQuestionIds = $data['questionIds'];
        $count=0;
        // test validation whether user answer every question or not
        foreach ($allQuestionIds as $quesId) {
            if(!array_key_exists('answerGroup_'.$quesId, $data)) {
                $count++;
            }
        }
        
        if($count != 0) {
            return redirect()->back()->withErrors(['Please answer all questions before submitting']);
        }
        // validation ends here
        $correctAnsIds = [];
        $wrongAnsIds = [];
        foreach ($allQuestionIds as $quesId) {
            $ansId = $data['answerGroup_'.$quesId];
            $ansDetails = \DB::table('question_answer')
            ->where('id', $ansId)
            ->first();
            if($ansDetails->correct_answer == 'TRUE') {
                $correctAnsIds[] = $ansDetails;
            } else {
                $wrongAnsIds[] = $ansDetails;
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
        $getTestAnalysis = $this->getTestAnalysis($testId);
        $correctQuestionDetails = $getTestAnalysis['correctQuestionDetails'];
        $wrongQuestionDetails = $getTestAnalysis['wrongQuestionDetails'];
        if($testId == null) {
            session()->forget('correctAnsIds');
            session()->forget('wrongAnsIds');
            session()->forget('test_status');
            session()->forget('testId');
        }
        $showBackBtn = true;
        if((strpos(url()->previous(),'take-exam') !== false)) {
            $showBackBtn = false;
        }
        return view('admin.exam.exam-result', compact('correctQuestionDetails', 'wrongQuestionDetails', 'showBackBtn'));
    }

    private function getTestAnalysis($testId = null) {
        $correctAnsIds = '';
        $wrongAnsIds = '';
        $test_status = '';
        if( $testId != null ) {
            $testHistory = StudentTestResults::where('user_id', auth()->user()->id)
                                            ->where('test_status', self::TEST_STATUS['COMPLETED'])
                                            ->where('id', $testId)
                                            ->get();
            $correctAnsIds = unserialize($testHistory[0]['correctAnsIds']);
            $wrongAnsIds   = unserialize($testHistory[0]['wrongAnsIds']);
            $test_status   = $testHistory[0]['test_status'];
        } else {
            $correctAnsIds = session('correctAnsIds');
            $wrongAnsIds = session('wrongAnsIds');
            $test_status = session('test_status');
        }
        $correctQuestionDetails = [];
        $wrongQuestionDetails = [];
        foreach($correctAnsIds as $correctQuestions) {
            $questDetails = Questions::where('id', $correctQuestions->question_id)->get()->toArray();
            $lessonVideo = Topics::where('lession_id', $questDetails[0]['lesson_id'])->get()->toArray();
            $questDetails[0]['video_url'] = (count($lessonVideo) != 0) ? $lessonVideo['0']['video_url'] : 'not_available';
            
            // all the prerequisite topics are found here
            $topicPreRequisite = (count($lessonVideo) != 0) ? TopicPreRequisite::where('topic_id', $lessonVideo['0']['id'])->get()->toArray() : [];
            $preRequisiteIds = '';
            foreach($topicPreRequisite as $preRequisite) {
                $topicName = Topics::where('id', $preRequisite['pre_requisite_topic_id'])->get()->toArray();
                if(!$topicName)
                    continue;

                $fullTopicName = $topicName[0]['topic_name'];
                $preRequisiteIds.=','.$fullTopicName;
            }
            $questDetails[0]['topic_pre_requisite'] = !empty($preRequisiteIds) ? substr($preRequisiteIds, 1) : '';

            $correctQuestionDetails[] = $questDetails[0];
        }
        foreach($wrongAnsIds as $wrongQuestions) {
            $questDetails = Questions::where('id', $wrongQuestions->question_id)->get()->toArray();
            $lessonVideo = Topics::where('lession_id', $questDetails[0]['lesson_id'])->get()->toArray();
            $questDetails[0]['video_url'] = (count($lessonVideo) != 0) ? $lessonVideo['0']['video_url'] : 'not_available';
            // all the prerequisite topics are found here
            $topicPreRequisite = (count($lessonVideo) != 0) ? TopicPreRequisite::where('topic_id', $lessonVideo['0']['id'])->get()->toArray() : [];
            $preRequisiteIds = '';
            foreach($topicPreRequisite as $preRequisite) {
                $topicName = Topics::where('id', $preRequisite['pre_requisite_topic_id'])->get()->toArray();
                if(!$topicName)
                    continue;

                $fullTopicName = $topicName[0]['topic_name'];
                $preRequisiteIds.=','.$fullTopicName;
            }
            $questDetails[0]['topic_pre_requisite'] = !empty($preRequisiteIds) ? substr($preRequisiteIds, 1) : '';
            $wrongQuestionDetails[] = $questDetails[0];
        }
        return [
            'correctQuestionDetails' => $correctQuestionDetails,
            'wrongQuestionDetails'   => $wrongQuestionDetails
        ];
    }

    public function getHistory() {
        abort_unless(\Gate::allows('exam_history'), 403);
        $testHistory = StudentTestResults::where('user_id', auth()->user()->id)->where('test_status', self::TEST_STATUS['COMPLETED'])->orderBy('id', 'desc')->get();
        $courses = Courses::all();
        $availableCourses = [];
        foreach ($courses->toArray() as $key => $value) {
            $availableCourses[$value['id']] = $value['course_name'];
        }
        
        return view('admin.exam.exam-history', compact('testHistory', 'availableCourses'));
    }

    public function lessonVideos(Request $request, $courseId, $testId = null) {
        abort_unless(\Gate::allows('exam_lesson_videos'), 403);
        if($testId != null && StudentTestResults::where('id', $testId)->exists()) {
            StudentTestResults::where('id', $testId)->delete();
        }
        $lessonVideos = $this->getAllTopicsVideosByCourseId($courseId);
        $showBackBtn = true;
        if((strpos(url()->previous(),'take-exam') !== false)) {
            $showBackBtn = false;
        }
        return view('admin.exam.lesson-videos', compact('lessonVideos', 'showBackBtn', 'courseId'));
    }


    private function getAllTopicsVideosByCourseId($courseId) {
        if($courseId) {
            return Lessons::where('course_id', $courseId)->paginate(15);
        }
        return [];
    }

}
