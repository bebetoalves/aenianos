<?php

namespace App\Filament\Resources;

use App\Filament\Notifications\DeletedAborted;
use App\Filament\Resources\ServerResource\Pages;
use App\Models\Server;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class ServerResource extends Resource
{
    protected static ?string $model = Server::class;

    protected static ?string $modelLabel = 'Servidor';

    protected static ?string $pluralModelLabel = 'Servidores';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('models.server.name'))
                    ->unique(ignoreRecord: true)
                    ->maxLength(30)
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('icon')
                    ->label(__('models.server.icon'))
                    ->image()
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('models.server.name')),

                Tables\Columns\TextColumn::make('links_count')
                    ->label(__('models.server.links'))
                    ->counts('links'),

                Tables\Columns\ImageColumn::make('icon')
                    ->label(__('models.server.icon'))
                    ->width(32)
                    ->height(32),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('models.common.created_at'))
                    ->date(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('models.common.updated_at'))
                    ->date(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Tables\Actions\DeleteAction $action, Server $record) {
                        if ($record->links()->exists()) {
                            DeletedAborted::notify('Por favor, apague os links associados a este servidor para continuar.');

                            $action->cancel();
                        }
                    }),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageServers::route('/'),
        ];
    }
}
