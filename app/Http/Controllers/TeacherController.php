<?php

namespace App\Http\Controllers;

use Auth;
use File;
use Storage;
use App\Teacher;
use App\BusinessHours;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TeacherController extends Controller
{
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
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'teacher_id' => 'required',
            'studio_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:50',
            'zip' => 'required|integer',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:50',
            'logo' => 'image|max:3200',
        ]);

        $phonef = preg_replace('/\D+/', '', $request->get('phone'));

        $studio = new Teacher([
            'teacher_id' => $request->get('teacher_id'),
            'studio_name' => $request->get('studio_name'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'address' => $request->get('address'),
            'address_2' => $request->get('address_2'),
            'city' => $request->get('city'),
            'state' => $request->get('state'),
            'zip' => $request->get('zip'),
            'email' => $request->get('email'),
            'phone' => $phonef
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
        $settings = Teacher::where('teacher_id', Auth::id())->get();
        return view('webapp.teacher.studiosettings', compact('settings', $settings));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'teacher_id' => 'required',
            'studio_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'address_2' => 'max:120',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:50',
            'zip' => 'required|integer',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:50',
            'logo' => 'image|max:3200',
        ]);
        $phonef = preg_replace('/\D+/', '', $request->get('phone'));

        $teacher = Teacher::where('teacher_id', '=', Auth::id())->first();
        $teacher->teacher_id = $request->teacher_id;
        $teacher->studio_name = $request->studio_name;
        $teacher->first_name = $request->first_name;
        $teacher->last_name = $request->last_name;
        $teacher->address = $request->address;
        $teacher->address_2 = $request->address_2;
        $teacher->city = $request->city;
        $teacher->state = $request->state;
        $teacher->zip = $request->zip;
        $teacher->email = $request->email;
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

    public function hoursUpdate(Request $request)
    {
        $input = $request->all();
        foreach ($input['rows'] as $index => $value) {

            if (!isset($value['active'])) {
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

    public function hoursSave(Request $request)
    {
        $input = $request->all();
        foreach ($input['rows'] as $index => $value) {

            if (!isset($value['active'])) {
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
