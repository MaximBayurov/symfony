<?php

namespace App\Homework;

use SymfonySkillbox\HomeworkBundle\Strategy;
use SymfonySkillbox\HomeworkBundle\Unit;

class ZergRushStrategy implements Strategy
{
    public function next(array $units, int $resource): ?Unit
    {
        usort($units, function (Unit $unit1, Unit $unit2) {
            return $unit2->getCost() <=> $unit1->getCost();
        });
    
        foreach ($units as $unit) {
            if ($unit->getCost() <= $resource) {
                return $unit::create();
            }
        }
    
        return null;
    }
    
}