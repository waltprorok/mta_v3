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
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class MessagesController extends Controller
{
    protected $casts = [
        'read' => 'boolean',
    ];

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
        $messages = Message::query()
            ->with('userFrom')
            ->where('user_id_to', Auth::id())
            ->notDeleted()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($messages);
    }

    public function reply(int $id = 0, string $subject = '', bool $new = false, int $status = Student::ACTIVE): View
    {
        $users = $this->messageService->getUsers($id, $status);
        $subject = $this->messageService->getSubjectString($subject, $new);

        return view('webapp.messages.reply')->with(['users' => $users, 'subject' => $subject, 'new' => $new]);
    }

    public function status(int $status = Student::ACTIVE): JsonResponse
    {
        $id = 0;
        $users = $this->messageService->getUsers($id, $status);
        $isTeacher = Auth::user()->teacher;

        return response()->json(['users' => $users, 'teacher' => $isTeacher]);
    }

    public function sendReply(Request $request): RedirectResponse
    {
        $request->validate([
            'to' => 'required',
            'subject' => 'required|min:2',
            'message' => 'required|min:3',
        ]);

        Message::query()->create([
            'user_id_from' => Auth::id(),
            'user_id_to' => $request->input('to'),
            'subject' => $request->input('subject'),
            'body' => $request->input('message'),
            'read' => 0,
            'deleted' => 0,
        ]);

        $toUser = User::query()->find($request->get('to'));

        Mail::to($toUser->email)->send(new MessageTo($request, $toUser));

        return redirect()->route('message.inbox')->with('success', 'Message sent successfully!');
    }

    public function send(SendMessageRequest $request): JsonResponse
    {
        // $request->get('all')
        // get all users by current status
        // loop through to create each record
        // loop through to email each user

        try {
            Message::query()->create([
                'user_id_from' => Auth::id(),
                'user_id_to' => $request->get('to'),
                'subject' => $request->get('subject'),
                'body' => $request->get('message'),
                'read' => 0,
                'deleted' => 0,
            ]);

            $toUser = User::query()->find($request->get('to'));

            Mail::to($toUser->email)->send(new MessageTo($request, $toUser));

        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
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

    public function read(int $id): View
    {
        $message = Message::query()
            ->with('userFrom')
            ->find($id);

        if ($message->user_id_from != Auth::id()) {
            $message->read = true;
            $message->save();
        }

        return view('webapp.messages.read')->with('message', $message);
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
