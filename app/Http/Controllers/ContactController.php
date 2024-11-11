<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function index()
    {
        return Contact::query()
            ->select('id', 'name', 'email', 'subject', 'message', 'reply', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function show(Contact $contact): JsonResponse
    {
        return response()->json($contact);
    }

    public function store(StoreContactRequest $request): JsonResponse
    {
        try {
            Contact::query()->create($request->all());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([], Response::HTTP_CREATED);
    }

    public function update(StoreContactRequest $request, Contact $contact): JsonResponse
    {
        try {
            $contact->update($request->all());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json();
    }

    public function updateReply(Request $request, Contact $contact): JsonResponse
    {
        try {
            $contact->update(['reply' => $request->get('reply')]);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json();
    }

    public function destroy(Contact $contact): JsonResponse
    {
        try {
            $contact->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json();
    }
}
