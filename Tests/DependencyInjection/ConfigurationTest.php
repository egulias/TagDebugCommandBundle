<?php

namespace Egulias\TagDebugCommandBundle\Test\DependencyInjection;

use Egulias\TagDebugCommandBundle\DependencyInjection\Configuration;
use Matthias\SymfonyConfigTest\PhpUnit\AbstractConfigurationTestCase;

class ConfigurationTest extends AbstractConfigurationTestCase
{
    protected function getConfiguration()
    {
        return new Configuration();
    }

    public function testEmptyConfiguration()
    {
        $expectedFilters = array();

        $this->assertConfigurationIsInvalid(array(
                array('filters' => array($expectedFilters))
            ),
            'class'
        );
    }


    public function testConfigureCustomFilter()
    {
        $filters = array(
            'class' => 'Egulias\TagDebugCommandBundle\Tests\Filter\Dummy',
            'params' => 1
        );

        $expectedFilters = array(
            'Egulias\TagDebugCommandBundle\Tests\Filter\Dummy' => array('params' => 1)
        );

        $this->assertProcessedConfigurationEquals(array(
               array('filters' => array($filters))
            ),
            array('filters' => $expectedFilters)
        );
    }
}