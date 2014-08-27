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
            'class' => 'Egulias\TagDebugCommandBundle\Tests\Dummy',
            'name' => 'dummy'
        );

        $expectedFilters = array(
            'Egulias\TagDebugCommandBundle\Tests\Dummy' => array('name' => 'dummy')
        );

        $this->assertProcessedConfigurationEquals(array(
               array('filters' => array($filters))
            ),
            array('filters' => $expectedFilters)
        );
    }

    public function testConfigureMultipleFilters()
    {
        $filters = array(
            array(
                'class' => 'Egulias\TagDebugCommandBundle\Tests\Dummy',
                'name' => 'dummy'
            ),
            array(
                'class' => 'Egulias\TagDebugCommandBundle\Tests\Dummy2',
                'name' => 'dummy2'
            )
        );

        $expectedFilters = array(
            'Egulias\TagDebugCommandBundle\Tests\Dummy' => array('name' => 'dummy'),
            'Egulias\TagDebugCommandBundle\Tests\Dummy2' => array('name' => 'dummy2')
        );

        $this->assertProcessedConfigurationEquals(array(
                array('filters' => $filters)
            ),
            array('filters' => $expectedFilters)
        );
    }
}