<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Student;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MessagesController extends Controller
{
    /**
     * @var MessageService
     */
    private $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function index(): JsonResponse
    {
        $user = Auth::user();

        $checkMessages = Message::query()
            ->with('userFrom:id,first_name,last_name')
            ->where('user_id_to', $user->id)
            ->notDeleted()
            ->get();

        if ($checkMessages->isEmpty()) {
            return response()->json(['user' => $user, 'messages' => []]);
        }

        $persons = Message::query()
            ->with('userFrom:id,first_name,last_name')
            ->where('user_id_to', $user->id)
            ->notDeleted()
            ->groupBy(['user_id_from'])
            ->orderByDesc('created_at')
            ->get();

        $messagesA = Message::query()
            ->with('userFrom:id,first_name,last_name,student,teacher,parent')
            ->where('user_id_to', $user->id)
            ->where('user_id_from', $persons->first()->user_id_from)
            ->notDeleted()
            ->get();

        $messagesB = Message::query()
            ->with('userFrom:id,first_name,last_name,student,teacher,parent')
            ->where('user_id_to', $persons->first()->user_id_from)
            ->where('user_id_from', $user->id)
            ->notDeleted()
            ->get();

        $messages = $messagesA->merge($messagesB)->sortBy('created_at')->values() ?? [];

        return response()->json(['persons' => $persons, 'messages' => $messages, 'user' => $user]);
    }

    public function show($id): JsonResponse
    {
        $user = Auth::user();

        $messagesFromA = Message::query()
            ->with('userFrom:id,first_name,last_name,student,teacher,parent,admin')
            ->where('user_id_from', $user->id)
            ->where('user_id_to', $id)
            ->notDeleted()
            ->get();

        $messagesFromB = Message::query()
            ->with('userFrom:id,first_name,last_name,student,teacher,parent,admin')
            ->where('user_id_from', $id)
            ->where('user_id_to', $user->id)
            ->notDeleted()
            ->get();

        $messages = $messagesFromA->merge($messagesFromB)->sortBy('id')->values();

        foreach ($messagesFromB as $message) {
            $message->update(['read' => true]);
        }

        $messages->sortByDesc('id');

        return response()->json(['messages' => $messages, 'user' => $user]);
    }

    public function status(int $status = Student::ACTIVE): JsonResponse
    {
        $persons = $this->messageService->getUsers($status);

        if ($persons->isEmpty()) {
            return response()->json();
        }

        return response()->json([
            'persons' => $persons,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            Message::query()->create([
                'user_id_from' => $request->get('user_id_from'),
                'user_id_to' => $request->input('user_id_to'),
                'body' => $request->input('body'),
                'read' => 0,
                'deleted' => 0,
            ]);

//            $toUser = User::query()->find($request->get('to'));
//            Mail::to($toUser->email)->send(new MessageTo($request, $toUser));

        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([], Response::HTTP_CREATED);
    }

}
