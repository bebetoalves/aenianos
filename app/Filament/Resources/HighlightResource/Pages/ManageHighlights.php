<?php

namespace App\Filament\Resources\HighlightResource\Pages;

use App\Filament\Resources\HighlightResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageHighlights extends ManageRecords
{
    protected static string $resource = HighlightResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
