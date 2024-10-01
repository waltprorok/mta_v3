<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Mail\WelcomeNewUserMail;
use App\Models\Student;
use App\Models\User;
use App\Services\PhoneNumberService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $phoneNumber = $this->phoneNumberService->stripPhoneNumber($request->get('phone'));

        $this->ifParentOptionIsFalse($request, $phoneNumber);

        $this->ifCheckParentAndOrEmailIsTrue($request, $phoneNumber);

        return response()->json([], Response::HTTP_CREATED);
    }

    /**
     * Edit Student Record
     */
    public function show(int $id): View
    {
        $student = Student::query()
            ->with('parent:id,first_name,last_name,email,parent')
            ->where(['id' => $id, 'teacher_id' => Auth::id()])
            ->first();

        return view('webapp.student.edit')->with('student', $student);
    }

    public function update(UpdateStudentRequest $request): RedirectResponse
    {
        $student = Student::query()
            ->with('parent')
            ->where('id', $request->get('student_id'))
            ->first();

        if ($request->get('parent_email') !== $student->parent->email) {
            $parent = User::query()->where('email', $student->parent->email)->first();
            $parent->email = $request->get('parent_email');
            $parent->save();

            Mail::to($parent->email)->send(new WelcomeNewUserMail($parent));
        }

        if ($request->get('parent_email') !== null && $student->parent === null) {
            try {
                // create new parent user
                $parentUser = User::firstOrCreate(
                    ['email' => $request->get('parent_email')],
                    ['first_name' => $request->get('parent_first_name'),
                    'last_name' => $request->get('parent_last_name'),
                    'email' => $request->get('parent_email'),
                    'password' => Hash::make($request->get('last_name')),
                    'parent' => true,
                    'terms' => true,
                ]);
                $student->parent_id = $parentUser->id;
                $student->save();

                Mail::to($parentUser->email)->send(new WelcomeNewUserMail($parentUser));
            } catch (\Exception $exception) {
                Log::info($exception->getMessage());
            }
        }

        $phoneNumber = $this->phoneNumberService->stripPhoneNumber($request->get('phone'));
        $parentPhoneNumber = $this->phoneNumberService->stripPhoneNumber($request->get('parent_phone'));
        // update student record
        $student->first_name = $request->get('first_name');
        $student->last_name = $request->get('last_name');
        $student->email = $request->get('email');
        $student->phone = $phoneNumber;
        $student->status = $request->get('status');
        $student->instrument = $request->get('instrument');
        $student->level = $request->get('level');
        $student->auto_schedule = $request->get('auto_schedule');
        $student->parent_phone = $parentPhoneNumber;
        $student->date_of_birth = $request->get('date_of_birth');
        $student->address = $request->get('address');
        $student->address_2 = $request->get('address_2');
        $student->city = $request->get('city');
        $student->state = $request->get('state');
        $student->zip = $request->get('zip');

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = date('Ymd_hms') . "." . $file->getClientOriginalExtension();
            Storage::disk('student')->put($fileName, File::get($file));
            $student->photo = $fileName;
        }

        $student->save();
        $this->createUserStudentEmail($request);

        return redirect()->back()->with('success', 'You successfully updated the student.');
    }

    /**
     * @param StoreStudentRequest $request
     * @param string|null $phoneNumber
     * @return void
     */
    private function ifParentOptionIsFalse(StoreStudentRequest $request, ?string $phoneNumber): void
    {
        if (! $request->get('add_parent') && $request->get('parent_email') == null) {
            // create new user
            try {
                $studentUser = User::firstOrCreate(
                    ['email' => $request->get('email')],
                    ['first_name' => $request->get('first_name'),
                        'last_name' => $request->get('last_name'),
                        'email' => $request->get('email'),
                        'password' => Hash::make(Str::random(10)),
                        'student' => true,
                        'terms' => true,
                    ]
                );
                // create new student
                Student::create([
                    'student_id' => $studentUser->id,
                    'teacher_id' => Auth::id(),
                    'first_name' => $request->get('first_name'),
                    'last_name' => $request->get('last_name'),
                    'phone' => $phoneNumber,
                    'email' => $request->get('email'),
                    'status' => $request->get('status'),
                ]);
                // send email
                Mail::to($studentUser->email)->send(new WelcomeNewUserMail($studentUser));
            } catch (\Exception $exception) {
                Log::info($exception->getMessage());
            }
        }
    }

    /**
     * @param StoreStudentRequest $request
     * @param string|null $phoneNumber
     * @return void
     */
    private function ifCheckParentAndOrEmailIsTrue(StoreStudentRequest $request, ?string $phoneNumber): void
    {
        if ($request->get('add_parent') && $request->get('parent_email')) {
            try {
                // create new parent user
                $parentUser = User::firstOrCreate(
                    ['email' => $request->get('parent_email')],
                    ['first_name' => $request->get('parent_first_name'),
                        'last_name' => $request->get('parent_last_name'),
                        'email' => $request->get('parent_email'),
                        'password' => Hash::make(Str::random(10)),
                        'parent' => true,
                        'terms' => true]
                );

                $user = $this->createUserStudentEmail($request);

                // create student
                Student::create([
                    'student_id' => $user ? $user->id : $parentUser->id,
                    'teacher_id' => Auth::id(),
                    'parent_id' => $parentUser->id,
                    'first_name' => $request->get('first_name'),
                    'last_name' => $request->get('last_name'),
                    'phone' => $phoneNumber,
                    'email' => $request->get('email'),
                    'status' => $request->get('status'),
                ]);

                // send email
                Mail::to($parentUser->email)->send(new WelcomeNewUserMail($parentUser));
            } catch (\Exception $exception) {
                Log::info($exception->getMessage());
            }
        }
    }

    /**
     * @param Request $request
     * @return User|array|Model
     */
    private function createUserStudentEmail(Request $request)
    {
        $userExists = User::where('email', $request->get('email'))->exists();
        $user = [];

        if ($userExists) {
            return $user;
        }

        // create student user
        if ($request->get('email') && $request->get('email') != null && ! $userExists) {
            $user = User::firstOrCreate(
                ['email' => $request->get('email')],
                ['first_name' => $request->get('first_name'),
                    'last_name' => $request->get('last_name'),
                    'email' => $request->get('email'),
                    'password' => Hash::make(Str::random(10)),
                    'student' => true,
                    'terms' => true,
                ]
            );
            // send email to new student user
            Mail::to($user->email)->send(new WelcomeNewUserMail($user));
        }

        return $user;
    }
}
