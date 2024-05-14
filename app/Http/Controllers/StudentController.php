<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Mail\WelcomeNewUserMail;
use App\Models\Student;
use App\Models\User;
use App\Services\PhoneNumberService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;


class StudentController extends Controller
{
    /**
     * @var PhoneNumberService
     */
    private $phoneNumberService;

    /**
     * @param PhoneNumberService $phoneNumberService
     */
    public function __construct(PhoneNumberService $phoneNumberService)
    {
        $this->phoneNumberService = $phoneNumberService;
    }

    /**
     * Student Profile Page
     */
    public function index(int $id): JsonResponse
    {
        $student = Student::query()
            ->where(['id' => $id, 'teacher_id' => Auth::id()])
            ->with('hasOneFutureLesson')
            ->first();

        return response()->json($student);
    }

    public function store(StoreStudentRequest $request): JsonResponse
    {
        try {
            $user = User::firstOrCreate(
                ['email' => $request->get('email')],
                ['first_name' => $request->get('first_name'),
                    'last_name' => $request->get('last_name'),
                    'email' => $request->get('email'),
                    'password' => Hash::make(Str::random(10)),
                    'student' => true,
                    'terms' => true]
            );

            $phoneNumber = $this->phoneNumberService->stripPhoneNumber($request->get('phone'));

            Student::create([
                'student_id' => $user->id,
                'teacher_id' => Auth::id(),
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'phone' => $phoneNumber,
                'email' => $request->get('email'),
                'status' => $request->get('status'),
            ]);

            Mail::to($user->email)->send(new WelcomeNewUserMail($user));
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
        }

        return response()->json([], Response::HTTP_CREATED);
    }

    /**
     * Edit Student Record
     */
    public function show(int $id): View
    {
        $students = Student::query()->where(['id' => $id, 'teacher_id' => Auth::id()])->get();

        return view('webapp.student.edit')->with('students', $students);
    }

    public function update(UpdateStudentRequest $request): RedirectResponse
    {
        $student = Student::query()->where('id', $request->get('student_id'))->first();
        $parentEmail = User::query()->where('email', $request->get('parent_email'))->first();

        if ($parentEmail !== null && $student->parent_email === null) {
            $parentEmail->parentStudentPivot()->toggle($student);
        } elseif ($request->get('parent_email') !== null && $parentEmail === null && $student->parent_email === null) {
            try {
                // create new parent user
                $user = User::query()->firstOrCreate([
                    'first_name' => $request->get('first_name'),
                    'last_name' => $request->get('last_name'),
                    'email' => $request->get('parent_email'),
                    'password' => Hash::make($request->get('last_name')),
                    'parent' => true,
                    'terms' => true,
                ]);
                // create new parent student pivot record
                $user->parentStudentPivot()->toggle($student);

                Mail::to($user->email)->send(new WelcomeNewUserMail($user));
            } catch (\Exception $exception) {
                Log::info($exception->getMessage());
            }
        }

        $phoneNumber = $this->phoneNumberService->stripPhoneNumber($request->get('phone'));

        // update student record
        $student->first_name = $request->get('first_name');
        $student->last_name = $request->get('last_name');
        $student->email = $request->get('email');
        $student->parent_email = $request->get('parent_email');
        $student->phone = $phoneNumber;
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
        }

        $student->save();

        return redirect()->back()->with('success', 'You successfully updated the student.');
    }
}
