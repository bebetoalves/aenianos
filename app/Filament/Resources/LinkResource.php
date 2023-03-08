<?php

namespace App\Filament\Resources;

use App\Enums\Quality;
use App\Filament\Resources\LinkResource\Pages;
use App\Models\Link;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class LinkResource extends Resource
{
    protected static ?string $model = Link::class;

    protected static ?string $modelLabel = 'Link';

    protected static ?string $pluralModelLabel = 'Links';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('models.link.name'))
                    ->maxLength(30)
                    ->required(),

                Forms\Components\TextInput::make('url')
                    ->label(__('models.link.url'))
                    ->url()
                    ->required(),

                Forms\Components\Select::make('project_id')
                    ->label(__('models.link.project'))
                    ->relationship('project', 'title')
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Select::make('quality')
                    ->label(__('models.link.quality'))
                    ->options(Quality::asSelectArray())
                    ->required(),

                Forms\Components\Select::make('server_id')
                    ->label(__('models.link.server'))
                    ->relationship('server', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('models.link.name'))
                    ->limit(30),

                Tables\Columns\TextColumn::make('project.title')
                    ->label(__('models.link.project'))
                    ->limit(30),

                Tables\Columns\TextColumn::make('quality')
                    ->label(__('models.link.quality'))
                    ->getStateUsing(fn (Link $record) => $record->quality->description),

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
            'index' => Pages\ManageLinks::route('/'),
        ];
    }
}
