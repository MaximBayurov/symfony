<?php

namespace App\Homework;

use SymfonySkillbox\HomeworkBundle\Unit;

class MyUnit extends Unit
{
    
    protected static function getBaseCharacteristics(): array
    {
        return [
            'name' => 'Тестовый юнит',
            'cost' => 113,
            'strength' => 13,
            'health' => 113,
        ];
    }
}