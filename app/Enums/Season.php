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
}
