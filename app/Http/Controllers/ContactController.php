<?php

namespace App\Http\Controllers;

use App\Contact;

class ContactController extends Controller
{
    public function index()
    {
        return Contact::latest()->orderBy('created_at', 'desc')->get();
    }
}
