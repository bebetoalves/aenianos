<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('pages.faq', ['faqs' => Faq::all()]);
    }
}
