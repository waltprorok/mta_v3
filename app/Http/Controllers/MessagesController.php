<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Student;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

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

        $persons = Message::query()
            ->with('userFrom:id,first_name,last_name')
            ->where('user_id_to', $user->id)
            ->notDeleted()
//            ->orderBy('created_at', 'desc')
            ->groupBy(['user_id_from'])
            ->latest()
            ->get();


        if ($persons->isEmpty()) {
            return response()->json(['messages' => [], 'user' => $user]);
        }

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

    public function getPersonMessages($id): JsonResponse
    {
        $user = Auth::user();

        $messagesFromA = Message::query()
            ->with('userFrom:id,first_name,last_name,student,teacher,parent')
            ->where('user_id_from', $user->id)
            ->where('user_id_to', $id)
            ->notDeleted()
            ->get();

        $messagesFromB = Message::query()
            ->with('userFrom:id,first_name,last_name,student,teacher,parent')
            ->where('user_id_from', $id)
            ->where('user_id_to', $user->id)
            ->notDeleted()
            ->get();

        $messages = $messagesFromA->merge($messagesFromB)->sortBy('id')->values();

        return response()->json(['messages' => $messages->sortByDesc('id'), 'user' => $user]);
    }

//    public function reply(Message $message, int $status = Student::ACTIVE)
//    {
//        $users = $this->messageService->getUsers($message->user_id_from, $status);
//        $subject = $this->messageService->getSubjectString($message->subject);
//
//        return response()->json([
//            'user' => $users,
//            'subject' => $subject,
//            'body' => $message->body,
//        ]);
//    }

    public function status(int $status = Student::ACTIVE): JsonResponse
    {
        $id = 0;
        $persons = $this->messageService->getUsers($id, $status);
        $isTeacher = Auth::user()->teacher;

        return response()->json([
            'persons' => $persons,
            'teacher' => $isTeacher
        ]);
    }

    public function send(Request $request): JsonResponse
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
//
//            Mail::to($toUser->email)->send(new MessageTo($request, $toUser));

        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([], Response::HTTP_CREATED);
    }

//    public function sent(): View
//    {
//        $messages = Message::query()
//            ->with('userTo')
//            ->where('user_id_from', Auth::id())
//            ->notDeleted()
//            ->orderBy('created_at', 'desc')
//            ->get();
//
//        return view('webapp.messages.sent')->with('messages', $messages);
//    }

    public function read(int $id): JsonResponse
    {
        $message = Message::query()
            ->with('userFrom:id,first_name,last_name,email')
            ->with('userTo:id,email')
            ->findOrFail($id);

        if ($message->user_id_from != Auth::id()) {
            $message->read = true;
            $message->save();
        }

        return response()->json(['message' => $message, 'author' => Auth::id()]);
    }

    public function delete(int $id): RedirectResponse
    {
        $message = Message::query()->find($id);
        $message->deleted = true;
        $message->save();

        return redirect()->route('message.inbox')->with('success', 'Message deleted successfully');
    }

    public function deleted(): View
    {
        $messages = Message::query()
            ->with('userFrom')
            ->where('user_id_to', Auth::id())
            ->isDeleted()
            ->get();

        return view('webapp.messages.deleted')->with('messages', $messages);
    }

//    public function return(int $id): RedirectResponse
//    {
//        $message = Message::query()->find($id);
//        $message->deleted = false;
//        $message->save();
//
//        return redirect()->to('/messages/inbox');
//    }
}
