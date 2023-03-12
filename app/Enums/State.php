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

    public function color(): string
    {
        return match ($this->value) {
            self::TL => 'lime',
            self::EC => 'violet',
            self::ED => 'green',
            self::TM => 'cyan',
            self::TS => 'pink',
            self::QC => 'amber',
            self::KR => 'red',
        };
    }
}
