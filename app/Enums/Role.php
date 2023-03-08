<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

class Role extends Enum implements LocalizedEnum
{
    const ADMIN = 'admin';

    const MODERATOR = 'mod';
}
