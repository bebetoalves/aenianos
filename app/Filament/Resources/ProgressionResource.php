<?php

namespace App\Filament\Resources;

use App\Enums\State;
use App\Filament\Resources\ProgressionResource\Pages\ManageProgressions;
use App\Models\Progression;
use Exception;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;

class ProgressionResource extends Resource
{
    protected static ?string $model = Progression::class;

    protected static ?string $modelLabel = 'Progresso';

    protected static ?string $pluralModelLabel = 'Progressos';

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('media')
                    ->label(__('models.progression.media'))
                    ->maxLength(15)
                    ->required(),

                Select::make('project_id')
                    ->label(__('models.progression.project'))
                    ->relationship('project', 'title')
                    ->searchable()
                    ->unique(ignoreRecord: true)
                    ->required(),

                Select::make('states')
                    ->label(__('models.progression.states'))
                    ->multiple()
                    ->maxItems(5)
                    ->options(State::asSelectArray())
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
                TextColumn::make('media')
                    ->label(__('models.progression.media')),

                TextColumn::make('project.title')
                    ->label(__('models.progression.project')),

                TagsColumn::make('states')
                    ->label(__('models.progression.states'))
                    ->getStateUsing(fn (Progression $record) => $record->states->pluck('description')->toArray()),

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
                DeleteAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageProgressions::route('/'),
        ];
    }
}
