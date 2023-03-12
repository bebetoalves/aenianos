<?php

namespace App\View\Components;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Footer extends Component
{
    public function render(): View
    {
        $popularPosts = Post::withCount('visits')
            ->orderBy('visits_count', 'desc')
            ->take(5)
            ->get();

        $latestProjects = Project::latest()
            ->limit(5)
            ->get();

        $popularProjects = Project::withCount('visits')
            ->orderBy('visits_count', 'desc')
            ->take(5)
            ->get();

        return view('components.footer', [
            'popularPosts' => $popularPosts,
            'latestProjects' => $latestProjects,
            'popularProjects' => $popularProjects,
        ]);
    }
}
