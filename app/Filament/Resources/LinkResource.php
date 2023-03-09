<?php

namespace App\Filament\Resources;

use App\Enums\Quality;
use App\Filament\Resources\LinkResource\Pages\ManageLinks;
use App\Models\Link;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;

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
                TextInput::make('name')
                    ->label(__('models.link.name'))
                    ->maxLength(30)
                    ->required(),

                TextInput::make('url')
                    ->label(__('models.link.url'))
                    ->url()
                    ->required(),

                Select::make('project_id')
                    ->label(__('models.link.project'))
                    ->relationship('project', 'title')
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),

                Select::make('quality')
                    ->label(__('models.link.quality'))
                    ->options(Quality::asSelectArray())
                    ->required(),

                Select::make('server_id')
                    ->label(__('models.link.server'))
                    ->relationship('server', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('models.link.name'))
                    ->limit(30),

                TextColumn::make('project.title')
                    ->label(__('models.link.project'))
                    ->limit(30),

                TextColumn::make('quality')
                    ->label(__('models.link.quality'))
                    ->getStateUsing(fn (Link $record) => $record->quality->description),

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
            'index' => ManageLinks::route('/'),
        ];
    }
}
