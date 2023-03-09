<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePosts extends ManageRecords
{
    protected static string $resource = PostResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = auth()->id();

                    return $data;
                }),
        ];
    }
}
