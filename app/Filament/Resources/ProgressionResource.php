<?php

namespace App\Filament\Resources;

use App\Enums\State;
use App\Filament\Resources\ProgressionResource\Pages;
use App\Models\Progression;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class ProgressionResource extends Resource
{
    protected static ?string $model = Progression::class;

    protected static ?string $modelLabel = 'Progresso';

    protected static ?string $pluralModelLabel = 'Progressos';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('models.progression.name'))
                    ->required(),

                Forms\Components\Select::make('project_id')
                    ->label(__('models.progression.project'))
                    ->relationship('project', 'title')
                    ->searchable()
                    ->unique(ignoreRecord: true)
                    ->required(),

                Forms\Components\Select::make('states')
                    ->label(__('models.progression.states'))
                    ->multiple()
                    ->maxItems(5)
                    ->options(State::asSelectArray())
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('models.progression.name')),

                Tables\Columns\TextColumn::make('project.title')
                    ->label(__('models.progression.project')),

                Tables\Columns\TagsColumn::make('states')
                    ->label(__('models.progression.states'))
                    ->getStateUsing(fn (Progression $record) => $record->states->pluck('description')->toArray()),

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
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProgressions::route('/'),
        ];
    }
}
