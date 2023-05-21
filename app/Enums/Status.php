<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

class Status extends Enum implements LocalizedEnum
{
    const ONGOING = 'ongoing';
    const COMPLETED = 'completed';
    const PAUSED = 'paused';
    const CANCELED = 'canceled';

    public function color(): string
    {
        return match ($this->value) {
            self::ONGOING => 'sky',
            self::COMPLETED => 'green',
            self::PAUSED => 'amber',
            self::CANCELED => 'red',
        };
    }
}
