<?php

namespace App\Http\Controllers;

use App\Mail\ContactForm;
use App\Mail\SupportEmail;
use App\Models\Support;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Storage;
use Throwable;

class SupportUserController extends Controller
{
    public function index()
    {
        return view('webapp.support.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject'    => 'required|min:3',
            'message'    => 'required|min:3',
            'attachment' => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx,txt,csv|max:7024',
        ]);

        $user = auth()->user();

        try {
            $support = Support::create([
                'name'    => $user->getFullNameAttribute(),
                'email'   => $user->email,
                'subject' => $validated['subject'],
                'message' => $validated['message'],
            ]);

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileName = $support->id . '_' . $file->getClientOriginalName();

                Storage::disk('support')->putFileAs('', $file, $fileName);

                $support->attachment = $fileName;
                $support->save();
            }

            Mail::to('waltprorok@gmail.com')
                ->queue(new ContactForm($support, 'support'));

            Mail::to($support->email)
                ->queue(new SupportEmail($support));

            return redirect()
                ->route('support')
                ->with('success', 'The support request was sent successfully');

        } catch (Throwable $e) {
            Log::error('Support submission failed', [
                'error'   => $e->getMessage(),
                'user_id' => $user->id,
            ]);

            return redirect()
                ->route('support')
                ->with('error', 'Something went wrong. Please try again.');
        }
    }
}
