<?php

use App\Enums\Category;
use App\Enums\Quality;
use App\Enums\Role;
use App\Enums\Season;
use App\Enums\State;

return [
    Category::class => [
        Category::SERIES => 'Série',
        Category::MOVIE => 'Filme',
        Category::OVA => 'OVA',
        Category::ONA => 'ONA',
        Category::SPECIAL => 'Especial',
    ],
    Season::class => [
        Season::WINTER => 'Inverno',
        Season::SPRING => 'Primavera',
        Season::SUMMER => 'Verão',
        Season::FALL => 'Outono',
    ],
    State::class => [
        State::TL => 'Tradução',
        State::EC => 'Encode',
        State::ED => 'Edição',
        State::TM => 'Timing',
        State::TS => 'Typesetting',
        State::QC => 'Revisão',
        State::KR => 'Karaoke',
    ],
    Quality::class => [
        Quality::UHD => 'Ultra HD',
        Quality::FHD => 'Full HD',
        Quality::HD => 'HD',
        Quality::SD => 'SD',
    ],
    Role::class => [
        Role::ADMIN => 'Administrador',
        Role::MODERATOR => 'Moderador',
    ],
];
