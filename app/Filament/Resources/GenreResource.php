<?php

namespace App\Filament\Resources;

use App\Filament\Notifications\DeletedAborted;
use App\Filament\Resources\GenreResource\Pages;
use App\Models\Genre;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class GenreResource extends Resource
{
    protected static ?string $model = Genre::class;

    protected static ?string $modelLabel = 'Gênero';

    protected static ?string $pluralModelLabel = 'Gêneros';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('models.genre.name'))
                    ->unique(ignoreRecord: true)
                    ->maxLength(30)
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('models.genre.name')),

                Tables\Columns\TextColumn::make('projects_count')
                    ->label(__('models.genre.projects'))
                    ->counts('projects'),

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
                    ->before(function (Tables\Actions\DeleteAction $action, Genre $record) {
                        if ($record->projects()->exists()) {
                            DeletedAborted::notify('Por favor, apague os projetos associados a este gênero para continuar.');

                            $action->cancel();
                        }
                    }),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageGenres::route('/'),
        ];
    }
}
