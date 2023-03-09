<?php

namespace App\Filament\Resources\HighlightResource\Pages;

use App\Filament\Resources\HighlightResource;
use Exception;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageHighlights extends ManageRecords
{
    protected static string $resource = HighlightResource::class;

    /**
     * @throws Exception
     */
    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
