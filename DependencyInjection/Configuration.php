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
            ->root('egulias_tag_debug_command')
            ->children()
                ->arrayNode('filters')
                ->defaultValue(array())
                ->useAttributeAsKey('class')
                ->prototype('array')
                    ->children()
                        ->scalarNode('name')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $tb;
    }

} 