<?php

namespace App\Http\Controllers;

use App\BusinessHours;
use App\Http\Requests\StoreScheduleAppt;
use App\Http\Requests\StoreStudent;
use App\Lesson;
use App\Student;
use App\Teacher;
use Auth;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Storage;


class StudentController extends Controller
{
    public $selectedDate;

    public function adminStudents()
    {
        $students = Student::all();
        return view('webapp.student.adminStudents', compact('students', $students));
    }

    public function index()
    {
        $teacher = Teacher::where('teacher_id', Auth::id())->first();

        if ($teacher == null) {
            return redirect('teacher')->with('info', 'Please fill out your Studio Settings first before entering students.');
        }

        $students = Student::with('hasOneLesson')
            ->where('teacher_id', Auth::id())
            ->where('status', 'Active')
            ->latestFirst()
            ->get();

        return view('webapp.student.index')->with('students', $students);
    }

    public function waitlist()
    {
        $waitlists = Student::with('teacher')->latestFirst()->where('teacher_id', Auth::id())->where('status', 'Waitlist')->get();

        return view('webapp.student.waitlist')->with('waitlists', $waitlists);
    }

    public function leads()
    {
        $leads = Student::with('teacher')->latestFirst()->where('teacher_id', Auth::id())->where('status', 'Lead')->get();

        return view('webapp.student.leads')->with('leads', $leads);
    }

    public function inactive()
    {
        $inactives = Student::with('teacher')->latestFirst()->where('teacher_id', Auth::id())->where('status', 'Inactive')->get();

        return view('webapp.student.inactive')->with('inactives', $inactives);
    }

    public function store(StoreStudent $request)
    {
        $email_exists = Student::where('email', $request->get('email'))->where('teacher_id', Auth::id())->first();

        if (isset($email_exists) && $email_exists->email == $request->get('email') && $email_exists->email != null) {
            return redirect()->back()->with('error', 'The email address is already in use.');
        }

        $phone = preg_replace('/\D/', '', $request->get('phone'));

        $student = new Student([
            'teacher_id' => Auth::id(),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email') ? $request->get('email') : null,
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

    public function edit($id)
    {
        $students = Student::where('id', $id)->where('teacher_id', Auth::id())->get();
        return view('webapp.student.edit')->with('students', $students);
    }

    public function lessons()
    {
        // ->whereDate('start_date', '>=', date('Y-m-d'))
        $lessons = Lesson::where('teacher_id', Auth::id())->orderBy('start_date', 'asc')->get();
        return view('webapp.student.lessons')->with('lessons', $lessons);
    }

    public function lessonsUpdate(Request $request)
    {
        // finish this update function
        foreach ($request as $data) {
            var_dump([$data->get('id'), $data->get('student_id'), $data->get('completed')]);
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

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

    /**
     * @param $day
     * @return int
     */
    public function dayOfWeek($day): int
    {
        switch ($day) {
            case "Monday":
                $today = 0;
                break;
            case "Tuesday":
                $today = 1;
                break;
            case "Wednesday":
                $today = 2;
                break;
            case "Thursday":
                $today = 3;
                break;
            case "Friday":
                $today = 4;
                break;
            case "Saturday":
                $today = 5;
                break;
            case "Sunday":
                $today = 6;
                break;
        }

        return $today;
    }

    public function schedule($id, $day = null)
    {
        $students = Student::where('id', $id)->where('teacher_id', Auth::id())->get();
        $businessHours = BusinessHours::where('teacher_id', Auth::id())->get();
        $lessons = Lesson::where('teacher_id', Auth::id())->orderBy('start_date', 'asc')->get();

        $startDate = $day;

        if ($day == null) {
            $day = date('l');
        } else {
            $day = Carbon::parse($day)->format('l');
        };

        $allTimes = [];

        $thisDay = $this->dayOfWeek($day);

        $amount = -30;

        foreach ($businessHours as $businessHour) {
            if ($businessHour->open_time <= $businessHour->close_time && $thisDay == $businessHour->day) {
                $diff = Carbon::parse($businessHour->open_time)->diff(Carbon::parse($businessHour->close_time));
                $amount = $amount + ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
            }

            if ($businessHour->active == 1 && $thisDay == $businessHour->day) {
                $openingTime = Carbon::parse($businessHour->open_time);
                array_push($allTimes, $openingTime->toTimeString());

                for ($i = 0; $i <= ($amount / 15); $i++) {
                    $thisOpeningTime = $openingTime->addMinutes(15);
                    array_push($allTimes, $thisOpeningTime->toTimeString());
                }
            }
        }

        $studentScheduled = false;

        foreach ($lessons as $lesson) {
            $lessonDay = Carbon::parse($lesson->start_date)->format('l');
            $lessonStartDate = $lesson->start_date;
            $lessonStartTime = Carbon::parse($lesson->start_date)->format('H:i:s');
            $lessonEndTime = Carbon::parse($lesson->end_date)->format('H:i:s');
            $lessonInterval = $lesson->interval;
            $studentLessonStart = Carbon::parse($lesson->start_date)->format('Y-m-d');

            if ($lesson->student_id == $id) {
                $studentScheduled = true;
            }

            if ($startDate != $studentLessonStart) {
                continue;
            }

            if ($lessonDay == $day && $lessonStartDate) {
                // remove time for a lesson that is already booked from all times
                foreach ($allTimes as $allTimeKey => $allTime) {

                    if ($allTime == $lessonStartTime && $lessonInterval == 15) {
                        unset($allTimes[$allTimeKey]);
                        unset($allTimes[$allTimeKey + 1]);
                    }

                    if ($allTime == $lessonStartTime && $lessonInterval == 30) {
                        unset($allTimes[$allTimeKey]);
                        unset($allTimes[$allTimeKey + 1]);
                    }

                    if ($allTime == $lessonStartTime && $lessonInterval == 45) {
                        unset($allTimes[$allTimeKey]);
                        unset($allTimes[$allTimeKey + 1]);
                    }

                    if ($allTime == $lessonStartTime && $lessonInterval == 60) {
                        unset($allTimes[$allTimeKey]);
                        unset($allTimes[$allTimeKey + 1]);
                    }

                    if ($allTime == $lessonEndTime) {
                        $allTimeKey = $allTimeKey - 1;
                        unset($allTimes[$allTimeKey]);
                    }
                }
            }
        }

        return view('webapp.student.schedule')
            ->with('students', $students)
            ->with('businessHours', $businessHours)
            ->with('allTimes', $allTimes)
            ->with('startDate', $startDate)
            ->with('studentScheduled', $studentScheduled);
    }

    public function scheduleSave(StoreScheduleAppt $request)
    {
        $begin = Carbon::parse($request->get('start_date'));
        $duration = date('H:i:s', strtotime($request->get('start_time') . ' +' . $request->get('end_time') . ' minutes'));
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

    public function scheduleEdit($student_id, $id, $day = null)
    {
        $students = Student::where('id', $student_id)->where('teacher_id', Auth::id())->get();
        $businessHours = BusinessHours::where('teacher_id', Auth::id())->get();
        $lessons = Lesson::where('student_id', $student_id)->where('id', $id)->where('teacher_id', Auth::id())->orderBy('start_date', 'asc')->get();
        $allLessons = Lesson::where('teacher_id', Auth::id())->orderBy('start_date', 'asc')->get();

        $startDate = $day;

        if ($day == null) {

            foreach ($lessons as $lesson) {
                $day = Carbon::parse($lesson->start_date)->format('l');
            }
        } else {
            $day = Carbon::parse($day)->format('l');
        }

        $allTimes = [];

        $thisDay = $this->dayOfWeek($day);

        $amount = -45;

        foreach ($businessHours as $businessHour) {
            if ($businessHour->open_time <= $businessHour->close_time && $thisDay == $businessHour->day) {
                $diff = Carbon::parse($businessHour->open_time)->diff(Carbon::parse($businessHour->close_time));
                $amount = $amount + ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
            }
            if ($businessHour->active == 1 && $thisDay == $businessHour->day) {
                $openingTime = Carbon::parse($businessHour->open_time);

                array_push($allTimes, $openingTime->toTimeString());

                for ($i = 0; $i <= ($amount / 14); $i++) {
                    $thisOpeningTime = $openingTime->addMinutes(15);
                    array_push($allTimes, $thisOpeningTime->toTimeString());
                }
            }
        }

        $lessonTimes = [];

        foreach ($allLessons as $allLesson) {
            foreach ($lessons as $lesson) {
                $studentLessonStart = Carbon::parse($lesson->start_date)->format('Y-m-d');
            }

            $allLessonsDay = Carbon::parse($allLesson->start_date)->format('Y-m-d');

            if ($allLessonsDay == $studentLessonStart || $allLessonsDay == $startDate) {

                $lessonStart = Carbon::parse($allLesson->start_date)->format('H:i:s');
                $lesson15Minutes = Carbon::parse($allLesson->start_date)->addMinute(15)->format('H:i:s');
                $lesson30Minutes = Carbon::parse($allLesson->start_date)->addMinute(30)->format('H:i:s');
                $lesson45Minutes = Carbon::parse($allLesson->start_date)->addMinute(45)->format('H:i:s');
                $lesson60Minutes = Carbon::parse($allLesson->start_date)->addMinute(60)->format('H:i:s');

                $lessonStartParse = Carbon::parse($allLesson->start_date);
                $lessonEndParse = Carbon::parse($allLesson->end_date);
                $diffInTime = $lessonEndParse->diffInSeconds($lessonStartParse);

                switch ($diffInTime) {
                    case 900:
                        $lessonTimes[] = $lessonStart;
                        break;
                    case 1800:
                        $lessonTimes[] = $lessonStart;
                        $lessonTimes[] = $lesson15Minutes;
                        break;
                    case 2700:
                        $lessonTimes[] = $lessonStart;
                        $lessonTimes[] = $lesson15Minutes;
                        $lessonTimes[] = $lesson30Minutes;
                        break;
                    case 3600:
                        $lessonTimes[] = $lessonStart;
                        $lessonTimes[] = $lesson15Minutes;
                        $lessonTimes[] = $lesson30Minutes;
                        $lessonTimes[] = $lesson45Minutes;
                        break;
                    default:
                        $lessonTimes[] = $lessonStart;
                }
            }
        }

        $allAvailableTimes = array_diff($allTimes, $lessonTimes);

        return view('webapp.student.scheduleEdit')
            ->with('lessons', $lessons)
            ->with('students', $students)
            ->with('allTimes', $allAvailableTimes)
            ->with('startDate', $startDate);
    }

    /**
     * @param Request $request
     * @return string
     * remove function not used
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
            return redirect()->back()->with('success', 'You successfully updated all the student\'s lessons.');
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
            return redirect(route('student.index'))->with('success', 'All the scheduled lessons have been deleted.');
        }
    }

    public function scheduleUpdate(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'start_date' => 'required|string',
            'end_time' => 'required|string'
        ]);

        $duration = Carbon::parse($request->get('start_time'))->addMinutes($request->get('end_time'))->format('H:i:s');

        $lesson = Lesson::where('student_id', $request->get('student_id'))->where('teacher_id', Auth::id())->where('id', $request->get('id'))->first();
        $lesson->id = $request->get('id');
        $lesson->student_id = $request->get('student_id');
        $lesson->teacher_id = Auth::id();
        $lesson->title = $request->get('title');
        $lesson->color = $request->get('color');
        $lesson->start_date = $request->get('start_date') . ' ' . $request->get('start_time');
        $lesson->end_date = $request->get('start_date') . ' ' . $duration;
        $lesson->interval = (int)$request->get('end_time');
        $lesson->update();
    }

    public function scheduleUpdateAll(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'start_date' => 'required|string',
            'end_time' => 'required|string'
        ]);

        $duration = Carbon::parse($request->get('start_time'))->addMinutes($request->get('end_time'))->format('H:i:s');

        $begin = Carbon::parse($request->get('start_date'));

        $lessons = Lesson::all()->where('student_id', $request->get('student_id'))->where('teacher_id', Auth::id());

        foreach ($lessons as $lesson) {
            $lesson->id = $lesson->id;
            $lesson->student_id = $request->get('student_id');
            $lesson->teacher_id = Auth::id();
            $lesson->title = $request->get('title');
            $lesson->color = $request->get('color');
            $lesson->start_date = $begin->format('Y-m-d') . ' ' . $request->get('start_time');
            $lesson->end_date = $begin->format('Y-m-d') . ' ' . $duration;
            $lesson->interval = (int)$request->get('end_time');
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
