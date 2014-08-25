<?php

namespace Egulias\TagDebugCommandBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
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

        $userFilters = $this->getUserFilters($config);
        $defaultFilters = $this->getDefaultFilters();

        $filters = array_merge($defaultFilters, $userFilters);
        $container->setParameter('tag_debug.filters', $filters);
    }

    protected function getUserFilters(array $config)
    {
        $filters = [];

        if (!isset($config['filters'])) {
            return $filters;
        }

        foreach ($config['filters'] as $filter => $params) {
            $this->validateFilterConfiguration($params);
            $filters[$filter] = $params['params'];
        }

        return $filters;
    }

    protected function validateFilterConfiguration($params)
    {
        if (!isset($params['params'])) {
            throw new InvalidArgumentException();
        }
    }

    private function getDefaultFilters()
    {
        return array(
            'Egulias\TagDebug\Tag\Filter\Name' => 1,
            'Egulias\TagDebug\Tag\Filter\AttributeName' => 1,
            'Egulias\TagDebug\Tag\Filter\AttributeValue' => 2,
            'Egulias\TagDebug\Tag\Filter\NameRegEx' => 2,
        );
    }
}