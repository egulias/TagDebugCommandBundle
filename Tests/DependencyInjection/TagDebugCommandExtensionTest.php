<?php

namespace Egulias\TagDebugCommandBundle\Test\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

use Egulias\TagDebugCommandBundle\DependencyInjection\TagDebugCommandExtension;

class TagDebugCommandExtensionTest extends \PHPUnit_Framework_TestCase
{
    private $extension;

    private $root;

    public function setUp()
    {
        $this->extension = $this->getExtension();
        $this->root      = "egulias_tag_debug";
    }

    public function testContainerHasFactoryDefinition()
    {
        $container = $this->getContainer();
        $this->extension->load(array(), $container);

        $this->assertTrue($container->hasDefinition("egulias.tag_filter_factory"));
    }

    public function testFactoryDefinitionHasMethodCalls()
    {
        $container = $this->getContainer();
        $this->extension->load(array(), $container);

        $factoryDefinition = $container->getDefinition("egulias.tag_filter_factory");
        $defaultFilters = 4;

        $calls = $factoryDefinition->getMethodCalls();

        $this->assertCount($defaultFilters, $calls);
        $this->assertEquals('name', $calls[0][1][0]);
        $this->assertEquals('attribute_name', $calls[1][1][0]);
        $this->assertEquals('attribute_value', $calls[2][1][0]);
        $this->assertEquals('name_regex', $calls[3][1][0]);
    }

    public function testRegisterUserFilters()
    {
        $container = $this->getContainer();
        $userFilters = array(
            'class' => 'Egulias\TagDebugCommandBundle\Tests\Dummy',
            'name' => 'dummy'
        );
        $this->extension->load(array(array('filters' => array($userFilters))), $container);

        $factoryDefinition = $container->getDefinition("egulias.tag_filter_factory");
        $filters = 5;

        $calls = $factoryDefinition->getMethodCalls();

        $this->assertCount($filters, $calls);
        $this->assertEquals('dummy', $calls[4][1][0]);
    }

    /**
     * @dataProvider wrongConfigProvider
     * @expectedException InvalidArgumentException
     */
    public function testHalfConfiguration($wrongConfig)
    {
        $expectedFilters = array('filters' => array($wrongConfig));
        $container = $this->getContainer();
        $this->extension->load(array($expectedFilters), $container);
    }

    public function wrongConfigProvider()
    {
        return array(
            array(array('class'=> 'clasname')),
        );

    }

    private function getExtension()
    {
        return new TagDebugCommandExtension();
    }

    private function getContainer()
    {
        $params = new ParameterBag(array('debug.container.dump' => __DIR__ . '/../appDevDebugProjectContainer.xml'));
        return new ContainerBuilder($params);
    }
}