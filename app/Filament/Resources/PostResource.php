<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $modelLabel = 'Postagem';

    protected static ?string $pluralModelLabel = 'Postagens';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('models.post.title'))
                    ->required()
                    ->maxLength(100)
                    ->columnSpanFull(),

                Forms\Components\MarkdownEditor::make('content')
                    ->label(__('models.post.content'))
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('image')
                    ->label(__('models.post.image'))
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('draft')
                    ->label(__('models.post.draft')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->limit(30)
                    ->label(__('models.post.title')),

                Tables\Columns\TextColumn::make('content')
                    ->limit(30)
                    ->label(__('models.post.content')),

                Tables\Columns\IconColumn::make('draft')
                    ->label(__('models.post.draft'))
                    ->boolean(),

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
            'index' => Pages\ManagePosts::route('/'),
        ];
    }
}
