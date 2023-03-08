<?php

namespace App\Filament\Notifications;

use Filament\Notifications\Notification;

class DeletedAborted
{
    public static function notify(string $message): void
    {
        Notification::make()
            ->danger()
            ->title('Houve um problema!')
            ->body($message)
            ->send();
    }
}
