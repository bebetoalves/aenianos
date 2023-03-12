<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

class Quality extends Enum implements LocalizedEnum
{
    const UHD = 'uhd';

    const FHD = 'fhd';

    const HD = 'hd';

    const SD = 'sd';

    public function order(): int
    {
        return match ($this->value) {
            self::UHD => 0,
            self::FHD => 1,
            self::HD => 2,
            self::SD => 3,
        };
    }
}
