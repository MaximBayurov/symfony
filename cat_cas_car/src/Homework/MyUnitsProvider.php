<?php

namespace App\Homework;

use SymfonySkillbox\HomeworkBundle\UnitProvider;
use SymfonySkillbox\HomeworkBundle\Units\Archer;
use SymfonySkillbox\HomeworkBundle\Units\Peasant;

class MyUnitsProvider implements UnitProvider
{
    
    /**
     * @inheritDoc
     */
    public function getUnits(): array
    {
        return [
            MyUnit::create(),
            Peasant::create(),
            Archer::create(),
        ];
    }
}