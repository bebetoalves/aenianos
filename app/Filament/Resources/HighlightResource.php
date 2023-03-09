<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HighlightResource\Pages;
use App\Models\Highlight;
use Closure;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class HighlightResource extends Resource
{
    protected static ?string $model = Highlight::class;

    protected static ?string $modelLabel = 'Destaque';

    protected static ?string $pluralModelLabel = 'Destaques';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project_id')
                    ->label(__('models.highlight.project'))
                    ->relationship('project', 'title')
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('cover')
                    ->label(__('models.highlight.cover'))
                    ->image()
                    ->hidden(fn (Closure $get): bool => $get('use_project_cover'))
                    ->required(fn (Closure $get): bool => ! $get('use_project_cover'))
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('use_project_cover')
                    ->label(__('models.highlight.use_project_cover'))
                    ->formatStateUsing(fn (Highlight|null $record) => $record?->cover === null)
                    ->reactive(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.title')
                    ->label(__('models.highlight.project')),

                Tables\Columns\ImageColumn::make('cover')
                    ->label(__('models.highlight.cover'))
                    ->getStateUsing(fn (Highlight $record) => $record->cover()),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('models.common.created_at'))
                    ->date(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('models.common.updated_at'))
                    ->date(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        if ($data['use_project_cover']) {
                            $data['cover'] = null;
                        }

                        return $data;
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageHighlights::route('/'),
        ];
    }
}
