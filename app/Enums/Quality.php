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
}
