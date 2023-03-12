<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Search extends Component
{
    public string $filter = '';

    public function render(): View
    {
        if (empty($this->filter)) {
            return view('livewire.search', ['results' => []]);
        }

        $posts = DB::table('posts')
            ->select([DB::raw("'posts.show' as route"), DB::raw("'Postagem' as type"), 'title', 'slug'])
            ->where('title', 'like', "%{$this->filter}%")
            ->limit(5);

        $projects = DB::table('projects')
            ->select([DB::raw("'projects.show' as route"), DB::raw("'Projeto' as type"), 'title', 'slug'])
            ->where('title', 'like', "%{$this->filter}%")
            ->limit(5);

        return view('livewire.search', ['results' => $posts->union($projects)->get()]);
    }
}
