<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function allReports()
    {
        return view('webapp.reports.all');
    }
}
