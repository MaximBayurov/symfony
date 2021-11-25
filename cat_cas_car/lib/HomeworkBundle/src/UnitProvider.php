<?php

namespace SymfonySkillbox\HomeworkBundle;

interface UnitProvider
{
    /**
     * @return Unit[]
     */
    public function getUnits(): array;
}