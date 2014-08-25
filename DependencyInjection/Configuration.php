<?php


namespace Egulias\TagDebugCommandBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $tb = new TreeBuilder();
        $tb
            ->root('egulias_tag_debug')
            ->children()
                ->arrayNode('filters')
                ->defaultValue(array())
                ->useAttributeAsKey('class')
                ->prototype('array')
                    ->children()
                        ->integerNode('params')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $tb;
    }

} 