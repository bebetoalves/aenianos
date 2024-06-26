<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('pages.about');
    }
}
