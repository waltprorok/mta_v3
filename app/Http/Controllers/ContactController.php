<?php

namespace App\Http\Controllers;

use App\Contact;
use Exception;
use Illuminate\Http\JsonResponse;

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
        return Contact::latest()->orderBy('created_at', 'desc')->get();
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
