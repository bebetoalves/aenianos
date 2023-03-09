<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages\ManageFaqs;
use App\Models\Faq;
use Exception;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $modelLabel = 'FAQ';

    protected static ?string $pluralModelLabel = 'Perguntas Frequentes';

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('question')
                    ->label(__('models.faq.question'))
                    ->maxLength(100)
                    ->required()
                    ->columnSpanFull(),

                MarkdownEditor::make('answer')
                    ->label(__('models.faq.answer'))
                    ->maxLength(280)
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
                TextColumn::make('question')
                    ->label(__('models.faq.question'))
                    ->limit(30),

                TextColumn::make('answer')
                    ->label(__('models.faq.answer'))
                    ->limit(30),

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
            'index' => ManageFaqs::route('/'),
        ];
    }
}
