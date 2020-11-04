<?php

namespace App\Http\Controllers;

use App\BusinessHours;
use App\Http\Requests\StoreStudent;
use App\Notifications\LessonConfirmation;
use App\Teacher;
use Auth;
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
            $student->save();

            return redirect()->route('student.index')->with('success', 'The student was added successfully.');
        }
    }

    public function edit($id)
    {
        $students = Student::where('id', $id)->where('teacher_id', Auth::id())->get();
        return view('webapp.student.edit')->with('students', $students);
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
        return view('webapp.student.schedule')->with('students', $students);
    }

    public function scheduleSave(Request $request)
    {
        $this->validate($request, [
            'student_id' => 'required|string',
            'title' => 'required|string',
            'start_date' => 'required|string',
        ]);

        $lesson = new Lesson();
        $lesson->student_id = $request->get('student_id');
        $lesson->teacher_id = Auth::id();
        $lesson->title = $request->get('title');
        $lesson->start_date = $request->get('start_date') . ' ' . $request->get('start_time');
        $lesson->end_date = $request->get('start_date') . ' ' . $request->get('end_time');
        $lesson->student->notify(new LessonConfirmation($lesson->student->first_name, $lesson->start_date));
        $lesson->save();
        return redirect()->back()->with('success', ' The student has been scheduled successfully.');
    }

    public function scheduleEdit($student_id, $id)
    {
        $lessons = Lesson::where('student_id', $student_id)->where('id', $id)->where('teacher_id', Auth::id())->get();
        return view('webapp.student.scheduleEdit')->with('lessons', $lessons);
    }

    public function scheduleUpdate(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'start_date' => 'required|string'
        ]);

        $lesson = Lesson::where('student_id', $request->get('student_id'))->where('id', $request->get('id'))->first();
        $lesson->id = $request->get('id');
        $lesson->student_id = $request->get('student_id');
        $lesson->teacher_id = Auth::id();
        $lesson->title = $request->get('title');
        $lesson->start_date = $request->get('start_date') . ' ' . $request->get('start_time');
        $lesson->end_date = $request->get('start_date') . ' ' . $request->get('end_time');
        $lesson->update();
        return redirect()->back()->with('success', 'You successfully updated the student\'s lesson.');
    }

    public function destroy($id)
    {
        $lesson = Lesson::find($id);
        $lesson->delete();
        return redirect(route('student.index'))->with('success', 'The scheduled lesson has been removed.');

    }

}
