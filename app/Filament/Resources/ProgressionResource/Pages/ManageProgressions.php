<?php

namespace App\Filament\Resources\ProgressionResource\Pages;

use App\Filament\Resources\ProgressionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageProgressions extends ManageRecords
{
    protected static string $resource = ProgressionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
