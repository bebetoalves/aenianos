<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages\ManagePosts;
use App\Models\Post;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

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
                TextInput::make('title')
                    ->label(__('models.post.title'))
                    ->required()
                    ->maxLength(100)
                    ->columnSpanFull(),

                MarkdownEditor::make('content')
                    ->label(__('models.post.content'))
                    ->required()
                    ->columnSpanFull(),

                FileUpload::make('image')
                    ->label(__('models.post.image'))
                    ->required()
                    ->columnSpanFull(),

                Toggle::make('draft')
                    ->label(__('models.post.draft')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->limit(30)
                    ->label(__('models.post.title')),

                TextColumn::make('user.name')
                    ->limit(30)
                    ->label(__('models.post.user')),

                IconColumn::make('draft')
                    ->label(__('models.post.draft'))
                    ->boolean(),

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
            'index' => ManagePosts::route('/'),
        ];
    }
}
