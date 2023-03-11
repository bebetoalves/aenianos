<?php

namespace App\Filament\Resources;

use App\Enums\Category;
use App\Enums\Season;
use App\Filament\Resources\ProjectResource\Pages\CreateProject;
use App\Filament\Resources\ProjectResource\Pages\EditProject;
use App\Filament\Resources\ProjectResource\Pages\ListProjects;
use App\Models\Project;
use Exception;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $modelLabel = 'Projeto';

    protected static ?string $pluralModelLabel = 'Projetos';

    protected static ?string $navigationIcon = 'heroicon-o-film';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('title')
                                    ->label(__('models.project.title'))
                                    ->maxLength(30)
                                    ->unique(ignoreRecord: true)
                                    ->required(),

                                TextInput::make('alternative_title')
                                    ->label(__('models.project.alternative_title'))
                                    ->maxLength(30),

                                TextInput::make('episodes')
                                    ->label(__('models.project.episodes'))
                                    ->maxLength(10)
                                    ->required(),
                            ]),

                        MarkdownEditor::make('synopsis')
                            ->label(__('models.project.synopsis'))
                            ->maxLength(560)
                            ->required(),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('year')
                                    ->label(__('models.project.year'))
                                    ->maxLength(4)
                                    ->required(),

                                Select::make('season')
                                    ->label(__('models.project.season'))
                                    ->options(Season::asSelectArray())
                                    ->required(),

                                Select::make('category')
                                    ->label(__('models.project.category'))
                                    ->options(Category::asSelectArray())
                                    ->required(),
                            ]),

                        Grid::make()
                            ->schema([
                                Select::make('genres')
                                    ->label(__('models.project.genres'))
                                    ->relationship('genres', 'name')
                                    ->multiple()
                                    ->maxItems(3)
                                    ->required(),

                                Select::make('related_project')
                                    ->label(__('models.project.related_project'))
                                    ->relationship('relatedProjects', 'title')
                                    ->multiple()
                                    ->searchable()
                                    ->maxItems(5),
                            ]),
                    ]),

                Section::make('Imagens')
                    ->schema([
                        FileUpload::make('miniature')
                            ->label(__('models.project.miniature'))
                            ->image()
                            ->required(),

                        FileUpload::make('cover')
                            ->label(__('models.project.cover'))
                            ->image()
                            ->required(),
                    ]),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('models.project.title'))
                    ->limit(30),

                BadgeColumn::make('links_count')
                    ->label(__('models.project.links'))
                    ->counts('links')
                    ->color('primary')
                    ->icon('heroicon-o-link'),

                TextColumn::make('category')
                    ->label(__('models.project.category'))
                    ->getStateUsing(fn (Project $record) => $record->category->description),

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
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'edit' => EditProject::route('/{record}/edit'),
        ];
    }
}
