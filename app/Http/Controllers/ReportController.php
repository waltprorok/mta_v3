<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function all()
    {
        return view('webapp.reports.all');
    }
}
