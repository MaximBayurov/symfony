<?php

namespace SymfonySkillbox\HomeworkBundle\Units;

use SymfonySkillbox\HomeworkBundle\Unit;

class Archer extends Unit
{
    protected static function getBaseCharacteristics(): array
    {
        return [
            'name' => 'Лучник',
            'cost' => 150,
            'strength' => 6,
            'health' => 50,
        ];
    }
}