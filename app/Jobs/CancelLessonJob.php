<?php

namespace App\Jobs;

use App\Mail\CancelLesson;
use App\Models\Message;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CancelLessonJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $lesson;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($lesson)
    {
        $this->lesson = $lesson;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $body = 'Lesson for ' . $this->lesson->title . ' has been '
                . $this->lesson->status . ' on this date: '
                . Carbon::parse($this->lesson->start_date)->format('D, M d, Y g:i') . ' - '
                . Carbon::parse($this->lesson->end_date)->format('g:i a') . '.';

            Message::query()->create([
                'user_id_from' => Auth::id(),
                'user_id_to' => $this->lesson->teacher_id,
                'body' => $body,
                'read' => 0,
                'deleted' => 0,
            ]);

            Mail::to($this->lesson->student->getTeacher->email)
                ->queue(new CancelLesson($this->lesson));

        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }

    }
}
