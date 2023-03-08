<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

class State extends Enum implements LocalizedEnum
{
    const TL = 'tl';

    const EC = 'ec';

    const ED = 'ed';

    const TM = 'tm';

    const TS = 'ts';

    const QC = 'qc';

    const KR = 'kr';
}
