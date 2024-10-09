<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Mail\MessageTo;
use App\Models\Message;
use App\Models\Student;
use App\Models\User;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
        $user = Auth::id();

        $persons = Message::query()
            ->with('userFrom:id,first_name,last_name')
            ->where('user_id_to', Auth::id())
            ->notDeleted()
            ->orderBy('created_at', 'desc')
            ->groupBy(['user_id_from'])
            ->get();

        $messagesA = Message::query()
            ->with('userFrom:id,first_name,last_name,student,teacher,parent')
            ->where('user_id_to', $user)
            ->where('user_id_from', $persons->first()->user_id_from)
            ->notDeleted()
            ->get();

        $messagesB = Message::query()
            ->with('userFrom:id,first_name,last_name,student,teacher,parent')
            ->where('user_id_to', $persons->first()->user_id_from)
            ->where('user_id_from', $user)
            ->notDeleted()
            ->get();

        $messages = $messagesA->merge($messagesB)->sortBy('id')->values();

        return response()->json(['persons' => $persons, 'messages' => $messages, 'user' => $user]);
    }

    public function getPersonMessages($id): JsonResponse
    {
        $user = Auth::id();

        $messagesFromA = Message::query()
            ->with('userFrom:id,first_name,last_name,student,teacher,parent')
            ->where('user_id_from', $user)
            ->where('user_id_to', $id)
            ->notDeleted()
            ->get();

        $messagesFromB = Message::query()
            ->with('userFrom:id,first_name,last_name,student,teacher,parent')
            ->where('user_id_from', $id)
            ->where('user_id_to', $user)
            ->notDeleted()
            ->get();

        $messages = $messagesFromA->merge($messagesFromB)->sortBy('id')->values();

        return response()->json(['messages' => $messages->sortByDesc('id'), 'user' => $user]);
    }

    public function reply(Message $message, int $status = Student::ACTIVE)
    {
        $users = $this->messageService->getUsers($message->user_id_from, $status);
        $subject = $this->messageService->getSubjectString($message->subject);

        return response()->json([
            'user' => $users,
            'subject' => $subject,
            'body' => $message->body,
        ]);
    }

    public function status(int $status = Student::ACTIVE): JsonResponse
    {
        $id = 0;
        $users = $this->messageService->getUsers($id, $status);
        $isTeacher = Auth::user()->teacher;

        return response()->json([
            'users' => $users,
            'teacher' => $isTeacher
        ]);
    }

    public function send(SendMessageRequest $request): JsonResponse
    {
        try {
            Message::query()->create([
                'user_id_from' => Auth::id(),
                'user_id_to' => $request->input('to'),
                'subject' => $request->input('subject'),
                'body' => $request->input('body'),
                'read' => 0,
                'deleted' => 0,
            ]);

            $toUser = User::query()->find($request->get('to'));

            Mail::to($toUser->email)->send(new MessageTo($request, $toUser));

        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([], Response::HTTP_CREATED);
    }

    public function sent(): View
    {
        $messages = Message::query()
            ->with('userTo')
            ->where('user_id_from', Auth::id())
            ->notDeleted()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('webapp.messages.sent')->with('messages', $messages);
    }

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

    public function return(int $id): RedirectResponse
    {
        $message = Message::query()->find($id);
        $message->deleted = false;
        $message->save();

        return redirect()->to('/messages/inbox');
    }
}
