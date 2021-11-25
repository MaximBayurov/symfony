<?php

namespace SymfonySkillbox\HomeworkBundle\Units;

use SymfonySkillbox\HomeworkBundle\Unit;

class Peasant extends Unit
{
    protected static function getBaseCharacteristics(): array
    {
        return [
            'name' => 'Крестьянин',
            'cost' => 33,
            'strength' => 1,
            'health' => 1,
        ];
    }
}