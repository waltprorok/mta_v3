<?php

namespace App\Http\Controllers;

use App\BusinessHours;
use App\Http\Requests\TeacherStoreSettings;
use App\Teacher;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function adminTeachers()
    {
        $teachers = Teacher::all();
        return view('webapp.teacher.adminTeachers', compact('teachers', $teachers));
    }

    public function index()
    {
        $teacher = Teacher::where('teacher_id', Auth::id())->first();

        if ($teacher == null) {
            return view('webapp.teacher.studioindex')->with('info', 'Please fill out your studio settings.');
        } else {
            return redirect()->route('teacher.editSettings');
        }
    }

    /**
     * @param TeacherStoreSettings $request
     * @return RedirectResponse
     */
    public function store(TeacherStoreSettings $request): RedirectResponse
    {
        $phone = preg_replace('/\D+/', '', $request->get('phone'));

        $studio = new Teacher([
            'teacher_id' => Auth::id(),
            'studio_name' => $request->get('studio_name'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'address' => $request->get('address'),
            'address_2' => $request->get('address_2'),
            'city' => $request->get('city'),
            'state' => $request->get('state'),
            'zip' => $request->get('zip'),
            'email' => $request->get('email'),
            'phone' => $phone
        ]);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = date('Ymd_hms') . "." . $file->getClientOriginalExtension();
            Storage::disk('teacher')->put($fileName, File::get($file));
            $studio->logo = $fileName;
        }

        $studio->save();

        return redirect()->route('teacher.editSettings')->with('success', 'You successfully saved your settings');
    }

    public function edit()
    {
        $user = User::find(Auth::id());
        $setting = $user->oneTeacher;

        return view('webapp.teacher.studiosettings', compact('setting', $setting));
    }

    public function update(TeacherStoreSettings $request)
    {
        $phonef = preg_replace('/\D+/', '', $request->get('phone'));
        $teacher = Teacher::where('teacher_id', '=', Auth::id())->first();
        $teacher->teacher_id = Auth::id();
        $teacher->studio_name = $request->get('studio_name');
        $teacher->first_name = $request->get('first_name');
        $teacher->last_name = $request->get('last_name');
        $teacher->address = $request->get('address');
        $teacher->address_2 = $request->get('address_2');
        $teacher->city = $request->get('city');
        $teacher->state = $request->get('state');
        $teacher->zip = $request->get('zip');
        $teacher->email = $request->get('email');
        $teacher->phone = $phonef;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = date('Ymd_hms') . "." . $file->getClientOriginalExtension();
            Storage::disk('teacher')->put($fileName, File::get($file));
            $teacher->logo = $fileName;
        } else {
            $teacher->save();
        }

        $teacher->save();

        return redirect()->back()->with('success', 'You successfully updated your settings');
    }

    public function profile()
    {
        $teachers = Teacher::where('teacher_id', Auth::id())->get();
        return view('webapp.teacher.profile')->with('teachers', $teachers);
    }

    public function payment()
    {
        return view('webapp.teacher.payment');
    }

    public function hours()
    {
        $hours = BusinessHours::where('teacher_id', Auth::id())->first();
        if ($hours == null) {
            return view('webapp.teacher.hours');
        } else {
            return redirect()->route('teacher.hoursView');
        }
    }

    public function hoursView()
    {
        $hours = BusinessHours::where('teacher_id', Auth::id())->get();
        return view('webapp.teacher.hoursView', compact('hours', $hours));
    }

    public function hoursUpdate(Request $request): RedirectResponse
    {
        $input = $request->all();
        foreach ($input['rows'] as $index => $value) {

            if (! isset($value['active'])) {
                $active = 0;
            } else {
                $active = $value['active'];
            }

            $hours = BusinessHours::where('teacher_id', '=', Auth::id())->where('day', '=', $value['day'])->first();
            $hours->teacher_id = Auth::id();
            $hours->day = $value['day'];
            $hours->active = $active;
            $hours->open_time = $value['open_time'];
            $hours->close_time = $value['close_time'];
            $hours->save();
        }

        return redirect()->back()->with('success', 'Business hours updated successfully!');
    }

    public function hoursSave(Request $request): RedirectResponse
    {
        $input = $request->all();

        foreach ($input['rows'] as $index => $value) {
            if (! isset($value['active'])) {
                $active = 0;
            } else {
                $active = $value['active'];
            }

            $items = new BusinessHours([
                'teacher_id' => Auth::id(),
                'day' => $value['day'],
                'active' => $active,
                'open_time' => $value['open_time'],
                'close_time' => $value['close_time'],
            ]);

            $items->save();
        }

        return redirect()->back()->with('success', 'Business hours saved successfully!');
    }
}
