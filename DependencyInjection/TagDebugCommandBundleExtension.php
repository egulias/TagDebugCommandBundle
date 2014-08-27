<?php

namespace Egulias\TagDebugCommandBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Extension\Extension;

class TagDebugCommandBundleExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $filters = $this->getFilters($config);
        $factoryDefinition = new Definition('Egulias\TagDebugCommandBundle\Filter\FilterFactory');

        foreach ($filters as $filterClass => $name) {
            $factoryDefinition->addMethodCall('register', array($name, $filterClass));
        }

        $container->setDefinition('egulias.tag_filter_factory', $factoryDefinition);

        $fetcherDefinition = new Definition('Egulias\TagDebug\Tag\TagFetcher', array($container));
        $container->setDefinition('egulias.tag_fetcher', $fetcherDefinition);
    }

    private function getUserFilters(array $config)
    {
        $filters = array();

        if (!isset($config['filters'])) {
            return $filters;
        }

        foreach ($config['filters'] as $class => $filter) {
            $this->validateConfig($filter);
            $filters[$class] = $filter['name'];
        }

        return $filters;
    }

    private function validateConfig(array $filter)
    {
        if(!isset($filter['name'])) {
            throw new \InvalidArgumentException('"name" is missing in egulias_tag_debug.filters configuration');
        }
    }

    private function getFilters($config)
    {
        $userFilters = $this->getUserFilters($config);
        $defaultFilters = $this->getDefaultFilters();

        return array_merge($defaultFilters, $userFilters);
    }

    private function getDefaultFilters()
    {
        return array(
            'Egulias\TagDebug\Tag\Filter\Name' => 'name' ,
            'Egulias\TagDebug\Tag\Filter\AttributeName' => 'attribute_name',
            'Egulias\TagDebug\Tag\Filter\AttributeValue' => 'attribute_value',
            'Egulias\TagDebug\Tag\Filter\NameRegEx' => 'name_regex',
        );
    }
}