<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\PasswordInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationLabel = 'Perfil';

    protected static string $view = 'filament.pages.profile';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Perfil';

    public ?string $name = null;

    public ?string $email = null;

    public ?string $current_password = null;

    public ?string $new_password = null;

    public ?string $new_password_confirmation = null;

    protected ?string $heading = 'Perfil';

    public function mount()
    {
        $this->fill([
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
        ]);
    }

    public function submit()
    {
        $this->form->getState();

        $state = array_filter([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->new_password ? Hash::make($this->new_password) : null,
        ]);

        $user = auth()->user();
        $user->update($state);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->notify('success', 'O seu perfil foi atualizado com sucesso.');
    }

    protected function getBreadcrumbs(): array
    {
        return [
            url()->current() => $this->heading,
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make(__('pages.profile.general'))
                ->columns()
                ->schema([
                    TextInput::make('name')
                        ->label(__('models.user.name'))
                        ->required(),

                    TextInput::make('email')
                        ->label(__('models.user.email'))
                        ->email()
                        ->unique(table: 'users', column: 'email', ignorable: auth()->user())
                        ->required(),
                ]),

            Section::make(__('pages.profile.password'))
                ->columns()
                ->schema([
                    PasswordInput::make('current_password')
                        ->label(__('models.user.current_password'))
                        ->requiredWith('new_password')
                        ->currentPassword()
                        ->columnSpan(1),

                    Grid::make()
                        ->schema([
                            PasswordInput::make('new_password')
                                ->label(__('models.user.new_password'))
                                ->confirmed(),

                            PasswordInput::make('new_password_confirmation')
                                ->label(__('models.user.password_confirmation'))
                                ->requiredWith('new_password'),
                        ]),
                ]),
        ];
    }
}
