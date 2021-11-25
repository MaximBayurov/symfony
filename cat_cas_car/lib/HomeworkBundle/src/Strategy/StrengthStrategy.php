<?php

namespace SymfonySkillbox\HomeworkBundle\Strategy;

use SymfonySkillbox\HomeworkBundle\Strategy;
use SymfonySkillbox\HomeworkBundle\Unit;
use SymfonySkillbox\HomeworkBundle\Units\Archer;

class StrengthStrategy implements Strategy
{
    
    /**
     * @inheritDoc
     */
    public function next(array $units, int $resource): ?Unit
    {
        usort($units, function (Unit $unit1, Unit $unit2) {
            return $unit2->getStrength() <=> $unit1->getStrength();
        });
    
        foreach ($units as $unit) {
            if ($unit->getCost() <= $resource) {
                return $unit::create();
            }
        }
        
        return null;
    }
}