<?php

namespace App\Filament\Resources\ServerResource\Pages;

use App\Filament\Resources\ServerResource;
use Exception;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageServers extends ManageRecords
{
    protected static string $resource = ServerResource::class;

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
