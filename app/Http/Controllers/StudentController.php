<?php

namespace App\Http\Controllers;

use App\BusinessHours;
use App\Http\Requests\StoreScheduleAppt;
use App\Http\Requests\StoreStudent;
use App\Notifications\LessonConfirmation;
use App\Teacher;
use Auth;
use Carbon\Carbon;
use DateTime;
use File;
use Illuminate\Support\Facades\DB;
use Storage;
use App\Student;
use App\Lesson;
use Illuminate\Http\Request;
use Nexmo\Laravel\Facade\Nexmo;


class StudentController extends Controller
{
    protected $studentLimit = 10;
    protected $waitlistLimit = 10;
    protected $leadLimit = 10;
    protected $inactiveLimit = 10;
    protected $lessonsLimit = 30;

    public function index()
    {
        $teacher = Teacher::where('teacher_id', Auth::id())->first();

        if ($teacher == null) {
            return redirect('teacher')->with('info', 'Please fill out your Studio Settings first before entering students.');
        }

        $students = Student::with('teacher')
            ->latestFirst()
            ->where('teacher_id', Auth::id())
            ->where('status', 'Active')
            ->paginate($this->studentLimit);

        return view('webapp.student.index')->with('students', $students);
    }

    public function waitlist()
    {
        $waitlists = Student::with('teacher')
            ->latestFirst()
            ->where('teacher_id', Auth::id())
            ->where('status', 'Waitlist')
            ->paginate($this->waitlistLimit);

        return view('webapp.student.waitlist')->with('waitlists', $waitlists);
    }

    public function leads()
    {
        $leads = Student::with('teacher')
            ->latestFirst()
            ->where('teacher_id', Auth::id())
            ->where('status', 'Lead')
            ->paginate($this->leadLimit);

        return view('webapp.student.leads')->with('leads', $leads);
    }

    public function inactive()
    {
        $inactives = Student::with('teacher')
            ->latestFirst()
            ->where('teacher_id', Auth::id())
            ->where('status', 'Inactive')
            ->paginate($this->inactiveLimit);

        return view('webapp.student.inactive')->with('inactives', $inactives);
    }

    public function store(StoreStudent $request)
    {
        $email_exists = Student::where('email', $request->get('email'))->where('teacher_id', Auth::id())->first();

        if ($email_exists && !null) {
            return redirect()->back()->with('error', 'The email address is already in use.');
        } else {
            $phone = preg_replace('/\D/', '', $request->get('phone'));
            $student = new Student([
                'teacher_id' => Auth::id(),
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email' => $request->get('email'),
                'phone' => $phone,
                'status' => $request->get('status'),
            ]);

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $fileName = date('Ymd_hms') . "." . $file->getClientOriginalExtension();
                Storage::disk('student')->put($fileName, File::get($file));
                $student->photo = $fileName;
            }
            $student->save();

            return redirect()->route('student.index')->with('success', 'The student was added successfully.');
        }
    }

    public function edit($id)
    {
        $students = Student::where('id', $id)->where('teacher_id', Auth::id())->get();
        return view('webapp.student.edit')->with('students', $students);
    }

    public function lessons()
    {
        $lessons = Lesson::where('teacher_id', Auth::id())->whereDate('start_date', '>=', date('Y-m-d'))->paginate($this->lessonsLimit);
        return view('webapp.student.lessons')->with('lessons', $lessons);
    }

    public function update(Request $request)
    {
        $phone = preg_replace('/\D/', '', $request->get('phone'));

        $student = Student::where('id', $request->get('student_id'))->first();
        $student->first_name = $request->get('first_name');
        $student->last_name = $request->get('last_name');
        $student->email = $request->get('email');
        $student->phone = $phone;
        $student->date_of_birth = $request->get('date_of_birth');
        $student->address = $request->get('address');
        $student->address_2 = $request->get('address_2');
        $student->city = $request->get('city');
        $student->state = $request->get('state');
        $student->zip = $request->get('zip');
        $student->instrument = $request->get('instrument');
        $student->status = $request->get('status');

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = date('Ymd_hms') . "." . $file->getClientOriginalExtension();
            Storage::disk('student')->put($fileName, File::get($file));
            $student->photo = $fileName;
        } else {
            $student->save();
        }

        $student->save();

        return redirect()->back()->with('success', 'You successfully updated the student.');
    }

    public function profile($id)
    {
        $students = Student::where('id', $id)->where('teacher_id', Auth::id())->get();

        return view('webapp.student.profile')->with('students', $students);
    }

    public function schedule($id)
    {
        $students = Student::where('id', $id)->where('teacher_id', Auth::id())->get();
        $businessHours = BusinessHours::where('teacher_id', Auth::id())->get();

        $today = Carbon::parse('today 8am'); // 2017-04-01 00:00:00
        $allTimes = [];
        array_push($allTimes, $today->toTimeString()); //add the 00:00 time before looping
        for ($i = 0; $i <= 55; $i ++){ //95 loops will give you everything from 00:00 to 23:45
            $today->addMinutes(15); // add 0, 15, 30, 45, 60, etc...
            array_push($allTimes, $today->toTimeString()); // inserts the time into the array like 00:00:00, 00:15:00, 00:30:00, etc.
        }

        return view('webapp.student.schedule')->with('students', $students)->with('businessHours', $businessHours)->with('allTimes', $allTimes);
    }

    public function scheduleSave(StoreScheduleAppt $request)
    {
        $begin = Carbon::parse($request->get('start_date'));
        $duration = date('h:i:s', strtotime($request->get('start_time') . ' +'.$request->get('end_time'). ' minutes'));
        $recurrence = (int)$request->get('recurrence');
        $end = Carbon::parse($request->get('start_date'))->addDays($recurrence);

        for ($i = $begin; $i <= $end; $i->modify('+7 day')) {
            $lesson = new Lesson();
            $lesson->student_id = $request->get('student_id');
            $lesson->teacher_id = Auth::id();
            $lesson->title = $request->get('title');
            $lesson->color = $request->get('color');
            $lesson->start_date = $i->format('Y-m-d') . ' ' . $request->get('start_time');
            $lesson->end_date = $i->format('Y-m-d') . ' ' . $duration;
            $lesson->interval = $request->get('end_time');
            $lesson->save();
        }

        return redirect()->back()->with('success', ' The student has been scheduled successfully.');
    }

    public function scheduleEdit($student_id, $id)
    {
        $lessons = Lesson::where('student_id', $student_id)->where('id', $id)->where('teacher_id', Auth::id())->get();

        return view('webapp.student.scheduleEdit')->with('lessons', $lessons);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function interval(Request $request)
    {
        $start_datetime = new Carbon($request->get('start_time'));
        $end_datetime = new Carbon($request->get('end_time'));
        return $interval = $start_datetime->diff($end_datetime)->format('%i');
    }

    public function scheduleUpdateStore(Request $request)
    {
        if ($request->input('action') == 'update') {
            $this->scheduleUpdate($request);
            return redirect()->back()->with('success', 'You successfully updated the student\'s lesson.');
        }
        if ($request->input('action') == 'updateAll') {
            $this->scheduleUpdateAll($request);
            return redirect()->back()->with('success', 'You successfully updated the student\'s lessons.');
        }
    }

    public function scheduledLessonDelete(Request $request, $id)
    {
        if ($request->input('action') == 'delete') {
            $this->destroy($id);
            return redirect(route('student.index'))->with('success', 'The scheduled lesson has been deleted.');
        }
        if ($request->input('action') == 'deleteAll') {
            $this->destroyAll($id);
            return redirect(route('student.index'))->with('success', 'The scheduled lessons have been deleted.');
        }
    }

    public function scheduleUpdate(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'start_date' => 'required|string'
        ]);

        $lesson = Lesson::where('student_id', $request->get('student_id'))->where('teacher_id', Auth::id())->where('id', $request->get('id'))->first();
        $lesson->id = $request->get('id');
        $lesson->student_id = $request->get('student_id');
        $lesson->teacher_id = Auth::id();
        $lesson->title = $request->get('title');
        $lesson->color = $request->get('color');
        $lesson->start_date = $request->get('start_date') . ' ' . $request->get('start_time');
        $lesson->end_date = $request->get('start_date') . ' ' . $request->get('end_time');
        $lesson->interval = (int)$this->interval($request);
        $lesson->update();
    }

    public function scheduleUpdateAll(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'start_date' => 'required|string'
        ]);

        $begin = Carbon::parse($request->get('start_date'));

        $lessons = Lesson::all()->where('student_id', $request->get('student_id'))->where('teacher_id', Auth::id());

        foreach ($lessons as $lesson) {
            $lesson->id = $lesson->id;
            $lesson->student_id = $request->get('student_id');
            $lesson->teacher_id = Auth::id();
            $lesson->title = $request->get('title');
            $lesson->color = $request->get('color');
            $lesson->start_date = $begin->format('Y-m-d') . ' ' . $request->get('start_time');
            $lesson->end_date = $begin->format('Y-m-d') . ' ' . $request->get('end_time');
            $lesson->interval = $request->get('interval');
            $lesson->update();
            $begin = $begin->modify('+7 day');
        }
    }

    public function destroy($id)
    {
        $lesson = Lesson::find($id);
        $lesson->delete();
    }

    public function destroyAll($id)
    {
        $studentId = Lesson::find($id);
        Lesson::where('student_id', $studentId->student_id)->where('teacher_id', Auth::id())->whereDate('start_date', '>=', date('Y-m-d'))->delete();
    }

}
