<?php

namespace App\Filament\Widgets;

use App\Models\Link;
use App\Models\Post;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Projetos', Project::count()),
            Card::make('Postagens', Post::count()),
            Card::make('Links Ativos', Link::where(['active' => true])->count()),
            Card::make('Links Quebrados', Link::where(['active' => false])->count()),
        ];
    }
}
