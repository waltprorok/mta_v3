<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ContactController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return ContactResource::collection(Contact::orderBy('created_at', 'desc')->get());
    }

    /**
     * @param Contact $contact
     * @return JsonResponse
     */
    public function show(Contact $contact): JsonResponse
    {
        return response()->json($contact, Response::HTTP_OK);
    }

    /**
     * @param StoreContactRequest $request
     * @return JsonResponse
     */
    public function store(StoreContactRequest $request): JsonResponse
    {
        Contact::create($request->all());

        $toast = ['success' => 'Contact saved successfully!'];

        return response()->json($toast, Response::HTTP_CREATED);
    }

    /**
     * @param StoreContactRequest $request
     * @param Contact $contact
     * @return JsonResponse
     */
    public function update(StoreContactRequest $request, Contact $contact): JsonResponse
    {
        $contact->update($request->all());

        $toast = ['success' => 'Contact has been updated!'];

        return response()->json($toast, Response::HTTP_OK);
    }

    /**
     * @param Contact $contact
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Contact $contact): JsonResponse
    {
        $contact->delete();

        $toast = ['success' => 'Contact has been deleted!'];

        return response()->json($toast, Response::HTTP_OK);
    }
}
