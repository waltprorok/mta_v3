<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * @return View
     */
    public function all(): View
    {
        return view('webapp.reports.all');
    }
}
