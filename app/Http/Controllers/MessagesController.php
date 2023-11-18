<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Mail\MessageTo;
use App\Models\Message;
use App\Models\User;
use App\Services\MessageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
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

    /**
     * @return View
     */
    public function index(): View
    {
        $messages = Message::query()
            ->with('userFrom')
            ->where('user_id_to', Auth::id())
            ->notDeleted()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('webapp.messages.inbox')->with('messages', $messages);
    }

    /**
     * @param int $id
     * @param string $subject
     * @param bool $new
     * @return View
     */
    public function create(int $id = 0, string $subject = '', bool $new = false): View
    {
        $users = $this->messageService->getUsers($id);
        $subject = $this->messageService->getSubjectString($subject, $new);

        return view('webapp.messages.create')->with(['users' => $users, 'subject' => $subject, 'new' => $new]);
    }

    /**
     * @param SendMessageRequest $request
     * @return RedirectResponse
     */
    public function send(SendMessageRequest $request): RedirectResponse
    {
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

    /**
     * @return View
     */
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

    /**
     * @param int $id
     * @return View
     */
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

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function delete(int $id): RedirectResponse
    {
        $message = Message::query()->find($id);
        $message->deleted = true;
        $message->save();

        return redirect()->route('message.inbox')->with('success', 'Message deleted successfully');
    }

    /**
     * @return View
     */
    public function deleted(): View
    {
        $messages = Message::query()
            ->with('userFrom')
            ->where('user_id_to', Auth::id())
            ->isDeleted()
            ->get();

        return view('webapp.messages.deleted')->with('messages', $messages);
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function return(int $id): RedirectResponse
    {
        $message = Message::query()->find($id);
        $message->deleted = false;
        $message->save();

        return redirect()->to('/messages/inbox');
    }
}
