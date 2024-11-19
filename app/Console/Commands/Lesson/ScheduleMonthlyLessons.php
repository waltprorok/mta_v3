<?php

namespace App\Console\Commands\Lesson;

use App\Models\Lesson;
use App\Models\Student;
use App\Services\StudentLessonService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScheduleMonthlyLessons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lessons:schedule-monthly';

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
        $start = now()->startOfMonth()->toDateTimeString();
        $end = now()->endOfMonth()->toDateTimeString();

        Student::query()
            ->where('status', Student::ACTIVE)
            ->where('auto_schedule', true)
            ->with(['lessons' => function ($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end]);
                $query->where('status', 'Scheduled');
                $query->where('recurrence', 'Monthly');
            }])
            ->with(['getTeacher.holidays' => function ($query) {
                $query->whereBetween('start_date', [
                        now()->addMonth()->startOfMonth()->toDateString(),
                        now()->addMonth()->endOfMonth()->toDateString()]
                );
                $query->where('all_day', true);
            }])
            ->with('parent:email')
            ->chunk(50, function ($students) use ($start, $end) {
                foreach ($students as $student) {
                    if ($student->lessons->isNotEmpty()) {
                        $startDateFirst = Carbon::parse($student->lessons->first()->start_date);
                        $endDateFirst = Carbon::parse($student->lessons->first()->end_date);

                        $startDateLast = Carbon::parse($student->lessons->last()->start_date);
                        $endDateLast = Carbon::parse($student->lessons->last()->end_date);

                        $firstLesson = $startDateLast->addWeek();
                        $end = $endDateLast->addMonth();

                        $minutes = $endDateFirst->diffInMinutes($startDateFirst);
                        $lessons = collect();

                        try {
                            for ($i = $firstLesson; $i <= $end; $i->modify('+7 day')) {
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
                                        if (! is_null($date) && $date->toDateString() == $lesson->start_date->toDateString()) {
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
                        } catch (\Exception $exception) {
                            Log::info($exception->getMessage());
                        }
                    }
                }
            });
    }
}
