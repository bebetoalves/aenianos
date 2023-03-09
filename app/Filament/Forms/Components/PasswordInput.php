<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\TextInput;

class PasswordInput
{
    public static function make(string $name): TextInput
    {
        return TextInput::make($name)
            ->password()
            ->minLength(8);
    }
}
