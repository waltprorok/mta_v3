<?php

namespace App\Console\Commands\Lesson;

use App\Models\Lesson;
use App\Models\Student;
use App\Services\StudentLessonService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
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
        Student::where('status', 1) // active
        ->where('auto_schedule', true)
            ->whereHas('lessons', function (Builder $query) {
                $query->whereBetween('start_date', [now()->startOfMonth(), now()->endOfMonth()]);
                $query->where('status', 'Scheduled');
            })
            ->with('lessons')
            ->with('getTeacher')
            ->with('parent')
            ->chunk(50, function ($students) {
                foreach ($students as $student) {
                    $startDate = Carbon::parse($student->lessons->last()->start_date);
                    $endDate = Carbon::parse($student->lessons->last()->end_date);
                    $nextMonth = $startDate->addMonth();
                    $dayOfWeek = $startDate->dayOfWeek + 1;
                    $firstLesson = $nextMonth->day($dayOfWeek);
                    $endOfMonth = Carbon::parse($nextMonth)->endOfMonth();
                    $diffInDays = $nextMonth->diffInDays($endOfMonth);
                    $end = Carbon::parse($nextMonth)->addDays($diffInDays);
                    $minutes = $endDate->diffInMinutes($startDate);
                    $lessons = collect();

                    try {
                        for ($i = $firstLesson; $i <= $end; $i->modify('+7 day')) {
                            $lesson = new Lesson();
                            $lesson->student_id = $student->lessons->last()->student_id;
                            $lesson->teacher_id = $student->teacher_id;
                            $lesson->billing_rate_id = $student->lessons->last()->billing_rate_id;
                            $lesson->title = $student->lessons->last()->title;
                            $lesson->color = $student->lessons->last()->color;
                            $lesson->start_date = $i->format('Y-m-d') . ' ' . $startDate->format('H:i:s');
                            $lesson->end_date = $i->format('Y-m-d') . ' ' . $endDate->format('H:i:s');
                            $lesson->interval = $minutes;
                            $lesson->save();
                            $lessons[] = $lesson;

                            Log::channel('lessons')->info($lesson->start_date . ' - ' . $lesson->end_date . ' - ' . $lesson->title);
                        }
                        $this->studentLessonService->emailLessonsToStudentParent($student, $lessons);
                    } catch (\Exception $exception) {
                        Log::info($exception->getMessage());
                    }
                }
            });
    }
}
