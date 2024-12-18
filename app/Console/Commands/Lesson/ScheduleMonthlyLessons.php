<?php

namespace App\Console\Commands\Lesson;

use App\Models\Lesson;
use App\Models\Student;
use App\Services\StudentLessonService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScheduleMonthlyLessons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lessons:schedule-monthly 
                            {--month : Input prompt which month of lessons to auto schedule}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically schedule monthly lessons';

    /**
     * @var StudentLessonService
     */
    private $studentLessonService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(StudentLessonService $studentLessonService)
    {
        parent::__construct();
        $this->studentLessonService = $studentLessonService;
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $hasMonth = $this->option('month');

        if ($hasMonth) {
            $monthName = $this->anticipate('Which month of lesson to schedule?', [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December',
            ]);
        }

        $lessonsStart = $hasMonth ? Carbon::parse($monthName)->startOfMonth()->toDateTimeString() : now()->startOfMonth()->toDateTimeString();
        $lessonsEnd = $hasMonth ? Carbon::parse($monthName)->endOfMonth()->toDateTimeString() : now()->endOfMonth()->toDateTimeString();

        $holidaysStart = $hasMonth ? Carbon::parse($monthName)->addMonth()->startOfMonth()->toDateTimeString() : now()->addMonth()->startOfMonth()->toDateTimeString();
        $holidaysEnd = $hasMonth ? Carbon::parse($monthName)->addMonth()->endOfMonth()->toDateTimeString() : now()->addMonth()->endOfMonth()->toDateTimeString();

        Student::query()
            ->where('status', Student::ACTIVE)
            ->where('auto_schedule', true)
            ->with(['lessons' => function ($query) use ($lessonsStart, $lessonsEnd) {
                $query->whereBetween('start_date', [$lessonsStart, $lessonsEnd]);
                $query->where('status', 'Scheduled');
                $query->where('recurrence', 'Monthly');
            }])
            ->with(['getTeacher.holidays' => function ($query) use ($holidaysStart, $holidaysEnd) {
                $query->where('all_day', true);
                $query->whereBetween('start_date', [$holidaysStart, $holidaysEnd]);
            }])
            ->with('parent:email')
            ->chunk(50, function ($students) use ($lessonsEnd) {
                $endWeekNumberInMonth = Carbon::parse($lessonsEnd)->weekNumberInMonth;
                foreach ($students as $student) {
                    if ($student->lessons->isNotEmpty() && $student->getTeacher->teacher->isOnTrialOrSubscribed()) {
                        $startDateFirst = Carbon::parse($student->lessons->first()->start_date);
                        $endDateFirst = Carbon::parse($student->lessons->first()->end_date);

                        $startDateLast = Carbon::parse($student->lessons->last()->start_date);
                        $endDateLast = Carbon::parse($student->lessons->last()->end_date);

                        $numberOfWeeks = ($endWeekNumberInMonth - $endDateLast->weekNumberInMonth);
                        $weeks = $numberOfWeeks == 0 ? 1 : $numberOfWeeks;
                        $startLesson = $startDateLast->addWeeks($weeks);
                        $endLesson = $endDateLast->addWeeks($numberOfWeeks)->addMonth();

                        $minutes = $endDateFirst->diffInMinutes($startDateFirst);
                        $lessons = collect();

                        try {
                            for ($i = $startLesson; $i <= $endLesson; $i->modify('+7 day')) {
                                $lesson = new Lesson();
                                $lesson->student_id = $student->lessons->first()->student_id;
                                $lesson->teacher_id = $student->teacher_id;
                                $lesson->billing_rate_id = $student->lessons->first()->billing_rate_id;
                                $lesson->title = $student->lessons->first()->title;
                                $lesson->color = $student->lessons->first()->color;
                                $lesson->start_date = $i->format('Y-m-d') . ' ' . $startDateFirst->format('H:i:s');

                                $holidays = $student->getTeacher->holidays->all();

                                $skipOverSave = false;

                                foreach ($holidays as $holiday) {
                                    $holidayDates = CarbonPeriod::create($holiday->start_date, $holiday->end_date);

                                    foreach ($holidayDates->toArray() as $date) {
                                        if (! is_null($date) && $date->toDateString() == Carbon::parse($lesson->start_date)->format('Y-m-d')) {
                                            $holiday['start_date'] = $i->format('Y-m-d');
                                            $lessons[] = $holiday->toArray();
                                            $skipOverSave = true;
                                        }
                                    }
                                }

                                if ($skipOverSave) {
                                    continue;
                                }

                                $lesson->end_date = $i->format('Y-m-d') . ' ' . $endDateFirst->format('H:i:s');
                                $lesson->interval = $minutes;
                                $lesson->recurrence = $student->lessons->last()->recurrence;
                                $lesson->save();
                                $lessons[] = $lesson;

                                Log::channel('lessons')->info($lesson->start_date . ' - ' . $lesson->end_date . ' - ' . $lesson->title);
                            }
                            $this->studentLessonService->emailLessonsToStudentParent($student, $lessons);
                        } catch (Exception $exception) {
                            Log::info($exception->getMessage());
                        }
                    }
                }
            });
    }
}
