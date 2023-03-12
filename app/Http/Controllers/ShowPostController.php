<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;

class ShowPostController extends Controller
{
    public function __invoke(Post $post): View
    {
        $post->visits()->updateOrCreate(['ip_address' => request()->ip()]);

        return view('pages.show-post', ['post' => $post]);
    }
}
