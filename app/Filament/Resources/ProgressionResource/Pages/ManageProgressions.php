<?php

namespace App\Filament\Resources\ProgressionResource\Pages;

use App\Filament\Resources\ProgressionResource;
use Exception;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageProgressions extends ManageRecords
{
    protected static string $resource = ProgressionResource::class;

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
