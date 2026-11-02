<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherSettingsRequest;
use App\Models\Teacher;
use App\Models\User;
use App\Services\PhoneNumberService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function __construct(
        private readonly PhoneNumberService $phoneNumberService
    ) {}

    public function index(): View
    {
        $teacher = Auth::user()->getTeacher;

        return view('webapp.teacher.studio', compact('teacher'));
    }

    public function store(StoreTeacherSettingsRequest $request): RedirectResponse
    {
        $this->saveStudio($request);

        return back()->with('success', 'You successfully saved your settings');
    }

    public function update(StoreTeacherSettingsRequest $request): RedirectResponse
    {
        $this->saveStudio($request);

        return back()->with('success', 'You successfully updated your settings');
    }

    private function saveStudio(StoreTeacherSettingsRequest $request): void
    {
        $data = $request->safe()->only([
            'studio_name',
            'first_name',
            'last_name',
            'address',
            'address_2',
            'city',
            'state',
            'zip',
            'email',
        ]);

        $data['phone'] = $this->phoneNumberService->stripPhoneNumber($request->input('phone'));

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('', 'teacher');
        }

        Teacher::updateOrCreate(['teacher_id' => Auth::id()], $data);
    }

    public function profile(): View
    {
        $teacher = User::with('getTeacher')->findOrFail(Auth::id());

        return view('webapp.teacher.profile', compact('teacher'));
    }

    public function adminTeachers()
    {
        return response()->json(
            Teacher::orderBy('first_name')->get()
        );
    }
}
