<?php

namespace App\Http\Livewire;

use App\Enums\Category;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ListProjects extends Component
{
    use WithPagination;

    public string $title = '';

    public string $category = '';

    public function updatingTitle()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $query = Project::orderBy('title');

        if (! empty($this->category)) {
            $query->where(['category' => $this->category]);
        }

        if (! empty($this->title)) {
            $query->where('title', 'like', '%' . $this->title . '%');
        }

        $projects = $query->paginate(12)
            ->withQueryString()
            ->onEachSide(1);

        $categories = Category::asSelectArray();

        return view('livewire.list-projects', [
            'projects' => $projects,
            'categories' => $categories,
        ])
            ->layout('components.layout', ['title' => 'Projetos']);
    }
}
