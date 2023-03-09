<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HighlightResource\Pages\ManageHighlights;
use App\Models\Highlight;
use Closure;
use Exception;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class HighlightResource extends Resource
{
    protected static ?string $model = Highlight::class;

    protected static ?string $modelLabel = 'Destaque';

    protected static ?string $pluralModelLabel = 'Destaques';

    protected static ?string $navigationIcon = 'heroicon-o-photograph';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('project_id')
                    ->label(__('models.highlight.project'))
                    ->relationship('project', 'title')
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),

                FileUpload::make('cover')
                    ->label(__('models.highlight.cover'))
                    ->image()
                    ->hidden(fn (Closure $get): bool => $get('use_project_cover'))
                    ->required(fn (Closure $get): bool => ! $get('use_project_cover'))
                    ->columnSpanFull(),

                Toggle::make('use_project_cover')
                    ->label(__('models.highlight.use_project_cover'))
                    ->formatStateUsing(fn (Highlight|null $record) => is_null($record?->cover))
                    ->reactive(),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project.title')
                    ->label(__('models.highlight.project')),

                ImageColumn::make('cover')
                    ->label(__('models.highlight.cover'))
                    ->getStateUsing(fn (Highlight $record) => $record->cover()),

                TextColumn::make('created_at')
                    ->label(__('models.common.created_at'))
                    ->date(),

                TextColumn::make('updated_at')
                    ->label(__('models.common.updated_at'))
                    ->date(),
            ])
            ->filters([])
            ->actions([
                EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        if ($data['use_project_cover']) {
                            $data['cover'] = null;
                        }

                        return $data;
                    }),
                DeleteAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageHighlights::route('/'),
        ];
    }
}
