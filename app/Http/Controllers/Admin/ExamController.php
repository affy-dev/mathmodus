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
    private const QUESTION_COUNT = 4;

    const TEST_STATUS = [
        'PENDING' => 'pending',
        'COMPLETED' => 'completed'
    ];

    public function index()
    {
        \Cache::forget('courseId');
        \Cache::forget('mcqs');
        \Cache::forget('testId');
        $courses = Courses::all();
        return view('admin.exam.index', compact('courses'));
    }

    public function takeExam(Request $request, $courseId, $lessonId = null) {
        if(!\Cache::has('courseId') && !\Cache::has('mcqs')) {
            $testFromLessonsTab = $lessonId != null ? true : false;
            if(!$testFromLessonsTab) {
                // $lessons = Lessons::pluck('id')->random(self::QUESTION_COUNT)->toArray();
                $lessons = [1,2,4,5];
            } else {
                $lessons =[$lessonId];
            }
            $mcqs = [];
            foreach ($lessons as $key => $lessionId) {
                $questionsLimit = $testFromLessonsTab ? 25 : 1;
                $quest = Questions::where('lesson_id', $lessionId)->inRandomOrder()->limit($questionsLimit)->get()->toArray();
                $questionAnswerDetails = [];
                foreach ($quest as $key => $value) {
                    $answerOptions = \DB::table('question_answer')
                    ->where('question_id', $value['id'])
                    ->first();
                    $value['answerText'] = $answerOptions->answer_text;
                    $value['answerId'] = $answerOptions->id;
                    $questionAnswerDetails[] = $value;
                }
                $mcqs[] = $questionAnswerDetails;
            }
            
            $testCreated = StudentTestResults::create([
                'user_id'=> auth()->user()->id, 
                'courseId' => $courseId,
                'test_status' => self::TEST_STATUS['PENDING']
            ]);

            $testId = $testCreated->id;
            session(['testId' => $testId]);
            
            $expiresAt = Carbon::now()->addMinutes(10);
            \Cache::put('courseId', $courseId, $expiresAt);
            \Cache::put('mcqs', $mcqs, $expiresAt);
            \Cache::put('testId', $testId, $expiresAt);
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
            return redirect()->route('admin.exams.index');
        }
        return view('admin.exam.take-exam', compact('courseId', 'mcqs', 'testId'));
    }

    public function submitExam(Request $request) {
        $data = $request->all();
        if(!StudentTestResults::where('id', $data['testId'])->exists() || StudentTestResults::where('id', $data['testId'])->where('test_status', self::TEST_STATUS['COMPLETED'])->exists()) {
            return redirect()->route('admin.exams.index')->withErrors(['Last Test had been alreadt completed or skipped by you. Start with another test']);
        }
        $courseId = $data['courseId'];
        $answerIds = $data['answerId'];
        $count=0;
        
        // test validation whether user answer every question or not
        foreach ($answerIds as $ansId) {
            if(!array_key_exists('answerGroup_'.$ansId, $data)) {
                $count++;
            }
        }
        
        if($count != 0) {
            return redirect()->back()->withErrors(['Please answer all questions before submitting']);
        }
        // validation ends here
        
        $correctAnsIds = [];
        $wrongAnsIds = [];
        foreach ($answerIds as $ansId) {
            $ansDetails = \DB::table('question_answer')
            ->where('id', $ansId)
            ->first();
            $userAns = $data['answerGroup_'.$ansDetails->id];
            if($ansDetails->correct_answer == $userAns) {
                $correctAnsIds[] = $ansDetails;
            } else {
                $wrongAnsIds[] = $ansDetails;
            }
        }
        StudentTestResults::where('id', session('testId'))->update([
            'user_id'=> auth()->user()->id, 
            'courseId' =>$courseId,
            'correctAnsIds' => serialize($correctAnsIds),
            'wrongAnsIds' => serialize($wrongAnsIds),
            'test_status' => self::TEST_STATUS['COMPLETED']
        ]);
        \Cache::forget('courseId');
        \Cache::forget('mcqs');
        \Cache::forget('testId');
        session(['correctAnsIds' => $correctAnsIds, 'wrongAnsIds' => $wrongAnsIds, 'test_status' => self::TEST_STATUS['COMPLETED']]);
        return redirect()->route('admin.exams.examresult');
    }

    public function examResults(Request $request, $testId = null) {
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
            $lessonVideo = Topics::where('id', $questDetails[0]['lesson_id'])->get()->toArray();
            $questDetails[0]['video_url'] = $lessonVideo['0']['video_url']; // topic video url
            
            // all the prerequisite topics are found here
            $topicPreRequisite = TopicPreRequisite::where('topic_id', $lessonVideo['0']['id'])->get()->toArray();
            $preRequisiteIds = '';
            foreach($topicPreRequisite as $preRequisite) {
                $topicName = Topics::where('id', $preRequisite['pre_requisite_topic_id'])->get()->toArray();
                if(!$topicName)
                    continue;

                $fullTopicName = $topicName[0]['topic_name'];
                $preRequisiteIds.=','.$fullTopicName;
            }
            $questDetails[0]['topic_pre_requisite'] = substr($preRequisiteIds, 1);

            $correctQuestionDetails[] = $questDetails[0];
        }
        foreach($wrongAnsIds as $wrongQuestions) {
            $questDetails = Questions::where('id', $wrongQuestions->question_id)->get()->toArray();
            $lessonVideo = Topics::where('id', $questDetails[0]['lesson_id'])->get()->toArray();
            $questDetails[0]['video_url'] = $lessonVideo['0']['video_url'];

            // all the prerequisite topics are found here
            $topicPreRequisite = TopicPreRequisite::where('topic_id', $lessonVideo['0']['id'])->get()->toArray();
            $preRequisiteIds = '';
            foreach($topicPreRequisite as $preRequisite) {
                $topicName = Topics::where('id', $preRequisite['pre_requisite_topic_id'])->get()->toArray();
                if(!$topicName)
                    continue;

                $fullTopicName = $topicName[0]['topic_name'];
                $preRequisiteIds.=','.$fullTopicName;
            }
            $questDetails[0]['topic_pre_requisite'] = substr($preRequisiteIds, 1);


            $wrongQuestionDetails[] = $questDetails[0];
        }
        return [
            'correctQuestionDetails' => $correctQuestionDetails,
            'wrongQuestionDetails'   => $wrongQuestionDetails
        ];
    }

    public function getHistory() {
        $testHistory = StudentTestResults::where('user_id', auth()->user()->id)->where('test_status', self::TEST_STATUS['COMPLETED'])->orderBy('id', 'desc')->get();
        $courses = Courses::all();
        $availableCourses = [];
        foreach ($courses->toArray() as $key => $value) {
            $availableCourses[$value['id']] = $value['course_name'];
        }
        
        return view('admin.exam.exam-history', compact('testHistory', 'availableCourses'));
    }

    public function lessonVideos(Request $request, $courseId, $testId = null) {

        if($testId != null && StudentTestResults::where('id', $testId)->exists()) {
            StudentTestResults::where('id', $testId)->delete();
        }
        $topicVideos = $this->getAllTopicsVideosByCourseId($courseId);
        $showBackBtn = true;
        if((strpos(url()->previous(),'take-exam') !== false)) {
            $showBackBtn = false;
        }
        return view('admin.exam.lesson-videos', compact('topicVideos', 'showBackBtn', 'courseId'));
    }


    private function getAllTopicsVideosByCourseId($courseId) {
        if($courseId) {
            $lessonIds = Lessons::where('course_id', $courseId)->pluck('id')->toArray();
            $topicsDetails = Topics::whereIn('id', $lessonIds)->paginate(16);
            return $topicsDetails;
        }
        return [];
    }

}
