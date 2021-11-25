<?php

namespace SymfonySkillbox\HomeworkBundle;

interface Strategy
{
    /**
     * @param Unit[] $units
     * @param int $resource
     * @return Unit|null
     */
    public function next(array $units, int $resource): ?Unit;
}