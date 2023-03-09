<?php

namespace App\Filament\Resources;

use App\Enums\Category;
use App\Enums\Season;
use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $modelLabel = 'Projeto';

    protected static ?string $pluralModelLabel = 'Projetos';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label(__('models.project.title'))
                                    ->maxLength(30)
                                    ->unique(ignoreRecord: true)
                                    ->required(),

                                Forms\Components\TextInput::make('alternative_title')
                                    ->label(__('models.project.alternative_title'))
                                    ->maxLength(30),

                                Forms\Components\TextInput::make('episodes')
                                    ->label(__('models.project.episodes'))
                                    ->maxLength(10)
                                    ->required(),
                            ]),

                        Forms\Components\MarkdownEditor::make('synopsis')
                            ->label(__('models.project.synopsis'))
                            ->required(),

                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('year')
                                    ->label(__('models.project.year'))
                                    ->maxLength(4)
                                    ->required(),

                                Forms\Components\Select::make('season')
                                    ->label(__('models.project.season'))
                                    ->options(Season::asSelectArray())
                                    ->required(),

                                Forms\Components\Select::make('category')
                                    ->label(__('models.project.category'))
                                    ->options(Category::asSelectArray())
                                    ->required(),

                                Forms\Components\Select::make('genres')
                                    ->label(__('models.project.genres'))
                                    ->relationship('genres', 'name')
                                    ->multiple()
                                    ->maxItems(3)
                                    ->required(),
                            ]),
                    ]),

                Forms\Components\Section::make('Imagens')
                    ->schema([
                        Forms\Components\FileUpload::make('miniature')
                            ->label(__('models.project.miniature'))
                            ->image()
                            ->required(),

                        Forms\Components\FileUpload::make('cover')
                            ->label(__('models.project.cover'))
                            ->image()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('models.project.title'))
                    ->limit(30),

                Tables\Columns\TextColumn::make('links_count')
                    ->label(__('models.project.links'))
                    ->counts('links'),

                Tables\Columns\TextColumn::make('category')
                    ->label(__('models.project.category'))
                    ->getStateUsing(fn (Project $record) => $record->category->description),

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
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
