<?php

namespace App\Enums;
enum PetGender: string
{
    case Male = 'male';
    case Female = 'female';

    public function label(): string
    {
        return match ($this) {
            self::Male => 'Mâle',
            self::Female => 'Femelle',
        };
    }
}
