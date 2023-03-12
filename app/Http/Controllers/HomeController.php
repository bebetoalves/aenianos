<?php

namespace App\Http\Controllers;

use App\Models\Highlight;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $posts = Post::latest()->limit(6)->get();
        $highlights = Highlight::latest()->limit(5)->get();

        return view('pages.home', [
            'posts' => $posts,
            'highlights' => $highlights,
        ]);
    }
}
