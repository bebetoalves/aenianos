<?php

namespace App\Filament\Resources;

use App\Enums\Role;
use App\Filament\Forms\Components\PasswordInput;
use App\Filament\Notifications\DeletedAborted;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Usuário';

    protected static ?string $pluralModelLabel = 'Usuários';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('models.user.name'))
                    ->maxLength(30)
                    ->required(),

                Forms\Components\TextInput::make('email')
                    ->label(__('models.user.email'))
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required(),

                PasswordInput::make('password')
                    ->label(__('models.user.password'))
                    ->required()
                    ->hiddenOn('edit'),

                PasswordInput::make('password')
                    ->label(__('models.user.password'))
                    ->hiddenOn('create'),

                Forms\Components\Select::make('role')
                    ->label(__('models.user.role'))
                    ->options(Role::asSelectArray())
                    ->default(Role::MODERATOR)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('models.user.name')),

                Tables\Columns\TextColumn::make('role')
                    ->label(__('models.user.role'))
                    ->getStateUsing(fn (User $record) => $record->role->description),

                Tables\Columns\TextColumn::make('posts_count')
                    ->label(__('models.user.posts'))
                    ->counts('posts'),

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
                        $password = $data['password'] ?? null;

                        if (null === $password) {
                            unset($data['password']);

                            return $data;
                        }

                        return array_merge($data, ['password' => bcrypt($password)]);
                    }),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Tables\Actions\DeleteAction $action, User $record) {
                        if (auth()->id() === $record->id) {
                            DeletedAborted::notify('Você não pode apagar o seu próprio usuário.');

                            $action->cancel();
                        }

                        if ($record->posts()->exists()) {
                            DeletedAborted::notify('Por favor, apague as postagens associadas a este usuário para continuar.');

                            $action->cancel();
                        }
                    }),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
