<?php

namespace App\Filament\Resources;

use App\Filament\Notifications\DeletedAborted;
use App\Filament\Resources\GenreResource\Pages\ManageGenres;
use App\Models\Genre;
use Exception;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;

class GenreResource extends Resource
{
    protected static ?string $model = Genre::class;

    protected static ?string $modelLabel = 'Gênero';

    protected static ?string $pluralModelLabel = 'Gêneros';

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('models.genre.name'))
                    ->unique(ignoreRecord: true)
                    ->maxLength(30)
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
                    ->label(__('models.genre.name')),

                TextColumn::make('projects_count')
                    ->label(__('models.genre.projects'))
                    ->counts('projects'),

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
                    ->before(function (DeleteAction $action, Genre $record) {
                        if ($record->projects()->exists()) {
                            DeletedAborted::notify('Apague os projetos associados a este gênero para continuar.');

                            $action->cancel();
                        }
                    }),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageGenres::route('/'),
        ];
    }
}
