<?php

namespace Egulias\TagDebugCommandBundle\Filter;

use \ReflectionClass;
use \InvalidArgumentException;

class FilterFactory
{
    const FILTER_INTERFACE = 'Egulias\TagDebug\Tag\Filter';
    protected $filterMap = array();

    public function register($name, $fqcn)
    {
        $this->filterMap[$name] = $fqcn;
    }

    public function createFromName($name, array $params)
    {
        $this->filterIsRegistered($name);
        $rflClass = new ReflectionClass($this->filterMap[$name]);

        $this->filterHasInterface($rflClass);

        return $rflClass->newInstanceArgs($params);
    }

    private function filterIsRegistered($name)
    {
        if(isset($this->filterMap[$name])) {
            return true;
        }

        throw new InvalidArgumentException(sprintf('%s has not been registered', $name));
    }

    private function filterHasInterface(ReflectionClass $rflClass)
    {
        if($rflClass->implementsInterface(self::FILTER_INTERFACE)) {
            return true;
        }

        throw new InvalidArgumentException(sprintf(
            '%s must implement %s',
            $rflClass->getName(),
            self::FILTER_INTERFACE)
        );
    }
}