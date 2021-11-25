<?php

namespace SymfonySkillbox\HomeworkBundle\Units;

use SymfonySkillbox\HomeworkBundle\Unit;

class Soldier extends Unit
{
    protected static function getBaseCharacteristics(): array
    {
        return [
            'name' => 'Солдат',
            'cost' => 100,
            'strength' => 5,
            'health' => 100,
        ];
    }
}