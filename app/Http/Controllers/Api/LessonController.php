<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function indexBlade()
    {
        return view('webapp.admin.lesson.index');
    }

    public function index()
    {
        return Lesson::with('lessonTeacherId')->orderBy('title')->orderBy('start_date', 'asc')->get();
    }

    /**
     * @param Request $request
     * @param Lesson $lesson
     * @return JsonResponse
     */
    public function update(Request $request, Lesson $lesson): JsonResponse
    {
        $lesson = Lesson::find($lesson->getAttribute('id'));
        $lesson->complete = $request->get('complete');
        $lesson->save();

        return response()->json($lesson, 200);
    }
}
