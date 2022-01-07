<?php

namespace App\Http\Controllers;

use App\Message;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MessagesController extends Controller
{
    public function index()
    {
        $messages = Message::with('userFrom')->where('user_id_to', Auth::id())->notDeleted()->get();

        return view('webapp.messages.inbox')->with('messages', $messages);
    }

    public function create(int $id = 0, string $subject = '')
    {
        if ($id === 0) {
            $users = User::whereHas('studentUsers', function ($query) {
                $query->where('teacher_id', Auth::id());
            })->firstNameAsc()->get();
        } else {
            $users = User::where('id', $id)->get();
        }

        if ($subject !== '') {
            $subject = 'Re: ' . $subject;
        }

        return view('webapp.messages.create')->with(['users' => $users, 'subject' => $subject]);
    }

    /**
     * @throws ValidationException
     */
    public function send(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'to' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);

        $message = new Message();
        $message->user_id_from = Auth::id();
        $message->user_id_to = $request->input('to');
        $message->subject = $request->input('subject');
        $message->body = $request->input('message');
        $message->read = 0;
        $message->deleted = 0;
        $message->save();

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
            ->deleted()
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
