<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Message;
use App\Services\MessageService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
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

    public function index()
    {
        $messages = Message::with('userFrom')->where('user_id_to', Auth::id())->notDeleted()->get();

        return view('webapp.messages.inbox')->with('messages', $messages);
    }

    /**
     * @param int $id
     * @param string $subject
     * @return Application|Factory|View
     */
    public function create(int $id = 0, string $subject = '')
    {
        $users = $this->messageService->getUsers($id);
        $subject = $this->messageService->getSubjectString($subject);

        return view('webapp.messages.create')->with(['users' => $users, 'subject' => $subject]);
    }

    /**
     * @throws ValidationException
     */
    public function send(SendMessageRequest $request): RedirectResponse
    {
        Message::create([
            'user_id_from' => Auth::id(),
            'user_id_to' => $request->input('to'),
            'subject' => $request->input('subject'),
            'body' => $request->input('message'),
            'read' => 0,
            'deleted' => 0,
        ]);

        return redirect()->route('message.inbox')->with('success', 'Message sent successfully!');
    }

    public function sent()
    {
        $messages = Message::with('userTo')
            ->where('user_id_from', Auth::id())
            ->orderBy('created', 'desc')
            ->notDeleted()
            ->get();

        return view('webapp.messages.sent')->with('messages', $messages);
    }

    public function read(int $id)
    {
        $message = Message::with('userFrom')->find($id);

        if ($message->user_id_from != Auth::id()) {
            $message->read = true;
            $message->save();
        }

        return view('webapp.messages.read')->with('message', $message);
    }

    public function delete(int $id): RedirectResponse
    {
        $message = Message::find($id);
        $message->deleted = true;
        $message->save();

        return redirect()->route('message.inbox')->with('success', 'Message deleted successfully');
    }

    public function deleted()
    {
        $messages = Message::with('userFrom')
            ->where('user_id_to', Auth::id())
            ->isDeleted()
            ->get();

        return view('webapp.messages.deleted')->with('messages', $messages);
    }

    public function return(int $id): RedirectResponse
    {
        $message = Message::find($id);
        $message->deleted = false;
        $message->save();

        return redirect()->to('/messages/inbox');
    }
}
