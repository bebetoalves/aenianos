<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ListPosts extends Component
{
    use WithPagination;

    public function render(): View
    {
        $posts = Post::latest()
            ->with('user')
            ->where(['draft' => false])
            ->paginate(10)
            ->onEachSide(1);

        return view('livewire.list-posts', ['posts' => $posts])
            ->layout('components.layout', ['title' => 'Postagens']);
    }
}
