<?php

namespace App\Http\Controllers\Api;

use App\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function indexBlade()
    {
        return view('webapp.contact.index');
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return Contact::orderBy('created_at', 'desc')->get();
    }

    /**
     * @param Contact $contact
     * @return Contact
     */
    public function show(Contact $contact): Contact
    {
        return $contact;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $contact = Contact::create($request->all());

        return response()->json($contact, 201);
    }

    /**
     * @param Request $request
     * @param Contact $contact
     * @return JsonResponse
     */
    public function update(Request $request, Contact $contact): JsonResponse
    {
        $contact->update($request->all());

        return response()->json($contact, 200);
    }

    /**
     * @param Contact $contact
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(Contact $contact): JsonResponse
    {
        $contact->delete();

        return response()->json(null, 204);
    }
}
