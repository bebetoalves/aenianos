<?php

namespace App\Filament\Forms\Components;

use Filament\Forms;

class PasswordInput
{
    public static function make(string $name): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make($name)
            ->password()
            ->minLength(8);
    }
}
