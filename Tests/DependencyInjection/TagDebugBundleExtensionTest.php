<?php

namespace Egulias\TagDebugCommandBundle\Test\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

use Egulias\TagDebugCommandBundle\DependencyInjection\TagDebugCommandBundleExtension;

class TagDebugBundleExtensionTest extends \PHPUnit_Framework_TestCase
{
    private $extension;

    private $root;

    public function setUp()
    {
        $this->extension = $this->getExtension();
        $this->root      = "tag_debug";
    }

    public function testGetConfigWithDefaultValues()
    {
        $container = $this->getContainer();
        $this->extension->load(array(), $container);

        $defaultFilters = array(
            'Egulias\TagDebug\Tag\Filter\Name' => 1,
            'Egulias\TagDebug\Tag\Filter\AttributeName' => 1,
            'Egulias\TagDebug\Tag\Filter\AttributeValue' => 2,
            'Egulias\TagDebug\Tag\Filter\NameRegEx' => 2,
        );

        $this->assertTrue($container->hasParameter($this->root . ".filters"));
        $this->assertEquals($defaultFilters, $container->getParameter($this->root . ".filters"));
    }

    public function testContainerHasUserFilters()
    {
        $userFilters = array(
            array(
                'class' => 'Egulias\Tests\Tag\Filter\Name',
                'params' => 1
            )
        );
        $expectedFilters = array(
            'Egulias\TagDebug\Tag\Filter\Name' => 1,
            'Egulias\TagDebug\Tag\Filter\AttributeName' => 1,
            'Egulias\TagDebug\Tag\Filter\AttributeValue' => 2,
            'Egulias\TagDebug\Tag\Filter\NameRegEx' => 2,
            'Egulias\Tests\Tag\Filter\Name' => 1,
        );

        $container = $this->getContainer();
        $this->extension->load(array(array('filters' => $userFilters)), $container);


        $this->assertTrue($container->hasParameter($this->root . ".filters"));
        $this->assertEquals($expectedFilters, $container->getParameter($this->root . ".filters"));
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
        return new TagDebugCommandBundleExtension();
    }

    private function getContainer()
    {
        $params = new ParameterBag(array('debug.container.dump' => __DIR__ . '/../appDevDebugProjectContainer.xml'));
        return new ContainerBuilder($params);
    }
}