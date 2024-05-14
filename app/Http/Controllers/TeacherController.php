<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherSettingsRequest;
use App\Models\Teacher;
use App\Models\User;
use App\Services\PhoneNumberService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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

    public function adminTeachers(): JsonResponse
    {
        return response()->json(Teacher::all());
    }

    public function index()
    {
        $teacher = User::with('getTeacher')->findOrFail(Auth::id());

        return $teacher->getTeacher == null ? $this->create() : $this->edit();
    }

    public function create()
    {
        return view('webapp.teacher.studioIndex');
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

        return redirect()->back()->with('success', 'You successfully saved your settings');
    }

    public function edit(): View
    {
        $setting = User::with('getTeacher')->findOrFail(Auth::id());

        return view('webapp.teacher.studiosettings', compact('setting', $setting));
    }

    public function update(StoreTeacherSettingsRequest $request): RedirectResponse
    {
        $phoneNumber = $this->phoneNumberService->stripPhoneNumber($request->get('phone'));

        $teacher = Teacher::query()->where('teacher_id', '=', Auth::id())->first();
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

    public function profile(): View
    {
        $teacher = User::with('getTeacher')->find(Auth::id());

        return view('webapp.teacher.profile')->with('teacher', $teacher);
    }
}
