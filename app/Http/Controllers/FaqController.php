<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('pages.faq', ['faqs' => Faq::all()]);
    }
}
