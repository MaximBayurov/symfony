<?php

namespace SymfonySkillbox\HomeworkBundle;

class UnitFactory
{
    public function __construct(
        private Strategy $strategy,
        private UnitProvider $unitProvider
    ) {
    
    }
    
    /**
     * Производит армию
     *
     * @param int $resources
     * @return Unit[]
     */
    public function produceUnits(int &$resources): array
    {
        $units = $this->unitProvider->getUnits();
    
        $army = [];
        while ($unit = $this->strategy->next($units, $resources)) {
            $army[] = $unit;
            $resources -= $unit->getCost();
        }
    
        return $army;
    }
}