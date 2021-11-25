<?php

namespace SymfonySkillbox\HomeworkBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('symfony_skillbox_homework');
        $rootNode = $treeBuilder->getRootNode();
        
        $rootNode
            ->children()
                ->scalarNode('strategy')->defaultValue('symfony_skillbox_homework.strategy_strength')->info('Стратегия производства юнитов по умолчанию по силе')->end()
                ->scalarNode('unit_provider')->defaultValue('symfony_skillbox_homework.unit_provider')->info('Провайдеров с юнитами по-умолчанию - базовый')->end()
            ->end()
        ;
        
        return $treeBuilder;
    }
}