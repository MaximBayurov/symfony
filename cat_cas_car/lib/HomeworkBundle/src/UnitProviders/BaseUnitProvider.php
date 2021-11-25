<?php

namespace SymfonySkillbox\HomeworkBundle\UnitProviders;

use SymfonySkillbox\HomeworkBundle\Unit;
use SymfonySkillbox\HomeworkBundle\UnitProvider;
use SymfonySkillbox\HomeworkBundle\Units\Archer;
use SymfonySkillbox\HomeworkBundle\Units\Peasant;
use SymfonySkillbox\HomeworkBundle\Units\Soldier;

class BaseUnitProvider implements UnitProvider
{
    /**
     * Формирует список доступных юнитов на фабрике
     *
     * @return Unit[]
     */
    public function getUnits(): array
    {
        return [
            Peasant::create(),
            Archer::create(),
            Soldier::create(),
        ];
    }
}