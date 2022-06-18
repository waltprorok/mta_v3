<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherSettingsRequest;
use App\Models\BusinessHours;
use App\Models\Teacher;
use App\Models\User;
use App\Services\PhoneNumberService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TeacherController extends Controller
{
    private $phoneNumberService;

    /**
     * @param PhoneNumberService $phoneNumberService
     */
    public function __construct(PhoneNumberService $phoneNumberService)
    {
        $this->phoneNumberService = $phoneNumberService;
    }

    /**
     * @return JsonResponse
     */
    public function adminTeachers(): JsonResponse
    {
        return response()->json(Teacher::all(), Response::HTTP_OK);
    }

    public function index()
    {
        $teacher = User::with('getTeacher')->findOrFail(Auth::id());

        if ($teacher->getTeacher == null) {
            return view('webapp.teacher.studioindex')->with('warning', 'Please fill out your studio settings.');
        }

        return redirect()->route('teacher.editSettings');
    }

    /**
     * @param StoreTeacherSettingsRequest $request
     * @return RedirectResponse
     */
    public function store(StoreTeacherSettingsRequest $request): RedirectResponse
    {
        $phoneNumber = $this->phoneNumberService->stripPhoneNumber($request->get('phone'));

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
            'phone' => $phoneNumber
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

    /**
     * @return View
     */
    public function edit(): View
    {
        $setting = User::with('getTeacher')->findOrFail(Auth::id());

        return view('webapp.teacher.studiosettings', compact('setting', $setting));
    }

    /**
     * @param StoreTeacherSettingsRequest $request
     * @return RedirectResponse
     */
    public function update(StoreTeacherSettingsRequest $request): RedirectResponse
    {
        $phoneNumber = $this->phoneNumberService->stripPhoneNumber($request->get('phone'));

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
        $teacher->phone = $phoneNumber;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = date('Ymd_hms') . "." . $file->getClientOriginalExtension();
            Storage::disk('teacher')->put($fileName, File::get($file));
            $teacher->logo = $fileName;
        }

        $teacher->save();

        return redirect()->back()->with('success', 'You successfully updated your settings');
    }

    /**
     * @return View
     */
    public function profile(): View
    {
        $teacher = User::with('getTeacher')->find(Auth::id());

        return view('webapp.teacher.profile')->with('teacher', $teacher);
    }

    /**
     * @return View
     */
    public function payment(): View
    {
        return view('webapp.teacher.payment');
    }

    public function hours()
    {
        $hours = BusinessHours::where('teacher_id', Auth::id())->first();

        if ($hours == null) {
            return view('webapp.teacher.hours');
        }

        return redirect()->route('teacher.hoursView');
    }

    /**
     * @return View
     */
    public function hoursView(): View
    {
        $hours = BusinessHours::where('teacher_id', Auth::id())->get();

        return view('webapp.teacher.hoursView', compact('hours', $hours));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function hoursSave(Request $request): RedirectResponse
    {
        $input = $request->all();

        foreach ($input['rows'] as $index => $value) {
            if (! isset($value['active'])) {
                $active = 0;
            } else {
                $active = $value['active'];
            }

            BusinessHours::create([
                'teacher_id' => Auth::id(),
                'day' => $value['day'],
                'active' => $active,
                'open_time' => $value['open_time'],
                'close_time' => $value['close_time'],
            ]);
        }

        return redirect()->back()->with('success', 'Business hours saved successfully!');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
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
}
