<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupportRequest;
use App\Models\Support;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SupportController extends Controller
{
    public function index()
    {
        return Support ::query()
            ->select('id', 'name', 'email', 'subject', 'message', 'attachment', 'reply', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function show(Support $support): JsonResponse
    {
        return response()->json($support);
    }

    public function store(StoreSupportRequest $request): RedirectResponse
    {
        try {
            Support::query()->create($request->all());

//            Mail::to('waltprorok@gmail.com')->send(new ContactForm($request));
//            Mail::to($request['email'])->queue(new SupportEmail($request));
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }

        return redirect()->route('support')->with('success', 'The support request was sent successfully');
    }

    public function update(StoreSupportRequest $request, Support $support): JsonResponse
    {
        try {
            $support->update($request->all());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json();
    }

    public function updateReply(Request $request, Support $support): JsonResponse
    {
        try {
            $support->update(['reply' => $request->get('reply')]);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json();
    }

    /**
     * @throws Exception
     */
    public function destroy(Support $support): JsonResponse
    {
        try {
            $support->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json();
    }
}
