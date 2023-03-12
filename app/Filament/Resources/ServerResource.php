<?php

namespace App\Filament\Resources;

use App\Filament\Notifications\DeletedAborted;
use App\Filament\Resources\ServerResource\Pages\ManageServers;
use App\Models\Server;
use Exception;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class ServerResource extends Resource
{
    protected static ?string $model = Server::class;

    protected static ?string $modelLabel = 'Servidor';

    protected static ?string $pluralModelLabel = 'Servidores';

    protected static ?string $navigationIcon = 'heroicon-o-server';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('models.server.name'))
                    ->unique(ignoreRecord: true)
                    ->maxLength(30)
                    ->required()
                    ->columnSpanFull(),

                FileUpload::make('icon')
                    ->label(__('models.server.icon'))
                    ->image()
                    ->imageResizeMode('cover')
                    ->imageResizeTargetWidth('32')
                    ->imageResizeTargetHeight('32')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('models.server.name')),

                BadgeColumn::make('links_count')
                    ->label(__('models.server.links'))
                    ->counts('links')
                    ->color('primary')
                    ->icon('heroicon-o-link'),

                ImageColumn::make('icon')
                    ->label(__('models.server.icon'))
                    ->width(32)
                    ->height(32),

                TextColumn::make('created_at')
                    ->label(__('models.common.created_at'))
                    ->date(),

                TextColumn::make('updated_at')
                    ->label(__('models.common.updated_at'))
                    ->date(),
            ])
            ->filters([])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (DeleteAction $action, Server $record) {
                        if ($record->links()->exists()) {
                            DeletedAborted::notify('Apague os links associados a esse servidor para continuar.');

                            $action->cancel();
                        }
                    }),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageServers::route('/'),
        ];
    }
}
