<?php

namespace SymfonySkillbox\HomeworkBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class SymfonySkillboxHomeworkBundleExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(dirname(__DIR__) . '/Resources/config'));
        $loader->load('services.xml');
        
        $configuration = $this->getConfiguration($configs, $container);
        
        $config = $this->processConfiguration($configuration, $configs);
        
        if ($config['strategy']) {
            $container->setAlias('symfony_skillbox_homework.strategy', $config['strategy']);
        }
        if ($config['unit_provider']) {
            $container->setAlias('symfony_skillbox_homework.unit_provider', $config['unit_provider']);
        }
    }
    
    public function getAlias()
    {
        return 'symfony_skillbox_homework';
    }
}