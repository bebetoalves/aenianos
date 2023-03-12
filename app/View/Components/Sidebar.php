<?php

namespace App\View\Components;

use App\Models\Progression;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public function render(): View
    {
        return view('components.sidebar', [
            'progression' => Progression::latest()
                ->with('project')
                ->limit(10)
                ->get(),
        ]);
    }
}
