<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Notifications\DeletedAborted;
use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (Actions\DeleteAction $action, Project $record) {
                    if ($record->links()->exists()) {
                        DeletedAborted::notify('Por favor, apague os links associados a este projeto para continuar.');

                        $action->cancel();
                    }
                }),
        ];
    }
}
