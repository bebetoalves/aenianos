<?php

namespace App\Filament\Resources\ServerResource\Pages;

use App\Filament\Resources\ServerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageServers extends ManageRecords
{
    protected static string $resource = ServerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
