<?php

namespace SymfonySkillbox\HomeworkBundle;

abstract class Unit
{
    abstract protected static function getBaseCharacteristics(): array;
    
    public static function create(): ?Unit
    {
        list(
            'name' => $name,
            'cost' => $cost,
            'strength' => $strength,
            'health' => $health,
        ) = static::getBaseCharacteristics();
        
        return new static(
            $name,
            $cost,
            $strength,
            $health
        );
    }
    
    protected function __construct(
        protected string $name,
        protected int $cost,
        protected int $strength,
        protected int $health
    ) {
    
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getCost(): int
    {
        return $this->cost;
    }
    
    public function getStrength(): int
    {
        return $this->strength;
    }
    
    public function getHealth(): int
    {
        return $this->health;
    }
}