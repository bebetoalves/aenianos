<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

class Season extends Enum implements LocalizedEnum
{
    const WINTER = 'winter';

    const SPRING = 'spring';

    const SUMMER = 'summer';

    const FALL = 'fall';

    public function color(): string
    {
        return match ($this->value) {
            self::WINTER => 'cyan',
            self::SPRING => 'emerald',
            self::SUMMER => 'pink',
            self::FALL => 'orange',
        };
    }
}
