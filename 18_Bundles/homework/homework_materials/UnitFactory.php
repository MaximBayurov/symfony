<?php

namespace SymfonySkillbox\HomeworkBundle;

class UnitFactory
{
    private $strategy;
    private $enableSolder;
    private $enableArcher;

    /**
     * @param StrategyInterface $strategy
     * @param bool $enableSolder
     * @param bool $enableArcher
     */
    public function __construct(StrategyInterface $strategy, bool $enableSolder, bool $enableArcher)
    {
        $this->strategy = $strategy;
        $this->enableSolder = $enableSolder;
        $this->enableArcher = $enableArcher;
    }

    /**
     * Производит армию
     *
     * @param int $resources
     * @return Unit[]
     */
    public function produceUnits(int $resources): array
    {
        $units = $this->getUnits();

        $army = [];
        while ($unit = $this->strategy->next($units, $resources)) {
            $army[] = $unit;
            $resources -= $unit->getCost();
        }

        return $army;
    }

    /**
     * Формирует список доступных юнитов на фабрике
     *
     * @return Unit[]
     */
    private function getUnits()
    {
        $units = [
            new Unit('Крестьянин', 33, 1, 1),
        ];

        if ($this->enableSolder) {
            $units[] = new Unit('Солдат', 100, 5, 100);
        }

        if ($this->enableArcher) {
            $units[] = new Unit('Лучник', 150, 6, 50);
        }

        return $units;
    }
}
