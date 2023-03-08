<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

class Category extends Enum implements LocalizedEnum
{
    const SERIES = 'series';

    const MOVIE = 'movie';

    const OVA = 'ova';

    const ONA = 'ona';

    const SPECIAL = 'special';
}
