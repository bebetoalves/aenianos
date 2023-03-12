<?php

namespace App\Http\Controllers;

use App\Enums\Quality;
use App\Models\Link;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class ShowProjectController extends Controller
{
    public function __invoke(Project $project): View
    {
        $links = Link::where(['project_id' => $project->getKey()])
            ->get()
            ->groupBy('quality')
            ->map(fn (Collection $data) => $data->sortBy('name', SORT_NATURAL)->groupBy('name'));

        $qualities = $links->keys()
            ->map(fn (string $value): Quality => Quality::fromValue($value))
            ->sortBy(fn (Quality $quality) => $quality->order());

        $project->visits()->updateOrCreate(['ip_address' => request()->ip()]);

        return view('pages.show-project', [
            'project' => $project,
            'qualities' => $qualities,
            'links' => $links,
        ]);
    }
}
