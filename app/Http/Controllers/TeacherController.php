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
            return view('webapp.teacher.studioindex')->with('success', 'Please fill out your studio settings.');
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
        $teacherId = Auth::id();
        $settings = Teacher::where('teacher_id', $teacherId)->get();
        return view('webapp.teacher.studiosettings', compact('settings'));
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
        $teacherId = Auth::user()->id;
        $phonef = preg_replace('/\D+/', '', $request->get('phone'));

        $teacher = Teacher::where('teacher_id', '=', $teacherId)->first();
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

    public function showChangePasswordForm()
    {
        return view('webapp.teacher.studiopw');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function changePassword(Request $request)
    {
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with('error',
                'Your current password does not match with the password you provided. Please try again.');
        }

        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            // Current password and new password are same
            return redirect()->back()->with('error',
                'New Password cannot be same as your current password. Please choose a different password.');
        }

        $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);

        // Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully!');
    }

    public function payment()
    {
        return view('webapp.teacher.payment');
    }

    public function hours()
    {

        return view('webapp.teacher.hours');
    }

    public function hoursSave(Request $request)
    {
        if ($request->get('monday_active') == null) {
            $monday_active = 0;
        } else {
            $monday_active = request('featured');
        }

        $hours = new BusinessHours([
            'teacher_id' => Auth::user()->id,
            'monday' => $request->get('monday'),
            'monday_active' => $monday_active,
            'monday_open_time' => $request->get('monday_open_time'),
            'monday_close_time' => $request->get('monday_close_time'),
            'tuesday' => $request->get('tuesday'),
            'tuesday_active' => $request->get('tuesday_active'),
            'tuesday_open_time' => $request->get('tuesday_open_time'),
            'tuesday_close_time' => $request->get('tuesday_close_time'),
            'wednesday' => $request->get('wednesday'),
            'wednesday_active' => $request->get('wednesday_active'),
            'wednesday_open_time' => $request->get('wednesday_open_time'),
            'wednesday_close_time' => $request->get('wednesday_close_time'),
            'thursday' => $request->get('thursday'),
            'thursday_active' => $request->get('thursday_active'),
            'thursday_open_time' => $request->get('thursday_open_time'),
            'thursday_close_time' => $request->get('thursday_close_time'),
            'friday' => $request->get('friday'),
            'friday_active' => $request->get('friday_active'),
            'friday_open_time' => $request->get('friday_open_time'),
            'friday_close_time' => $request->get('friday_close_time'),
            'saturday' => $request->get('saturday'),
            'saturday_active' => $request->get('saturday_active'),
            'saturday_open_time' => $request->get('saturday_open_time'),
            'saturday_close_time' => $request->get('saturday_close_time'),
            'sunday' => $request->get('sunday'),
            'sunday_active' => $request->get('sunday_active'),
            'sunday_open_time' => $request->get('sunday_open_time'),
            'sunday_close_time' => $request->get('sunday_close_time'),
        ]);

        $hours->save();

        return redirect()->back()->with('success', 'Business hours saved successfully!');
    }

}
