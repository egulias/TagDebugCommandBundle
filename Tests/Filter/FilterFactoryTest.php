<?php

namespace Egulias\TagDebugCommandBundle\Test\Filter;

use Egulias\TagDebugCommandBundle\Filter\FilterFactory;

class FilterFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterAndCreateAFilter()
    {
        $fqcn = 'Egulias\TagDebug\Tag\Filter\Name';
        $name = 'name';

        $factory = new FilterFactory();

        $this->assertNull($factory->register($name, $fqcn));
        $this->assertInstanceOf($fqcn, $factory->createFromName($name, ['param']));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCreateANotRegisteredFilter()
    {
        $name = 'name';

        $factory = new FilterFactory();

        $factory->createFromName($name, ['param']);
    }

    public function testCreateFilterInstanceWithMoreConstructorArgumentsThanRequiredIsNotReported()
    {
        $fqcn = 'Egulias\TagDebug\Tag\Filter\Name';
        $name = 'name';

        $factory = new FilterFactory();

        $factory->register($name, $fqcn);
        $this->assertInstanceOf($fqcn, $factory->createFromName($name, ['param', 'extra']));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testClassNotImplementsFilterInterface()
    {
        $fqcn = 'Egulias\TagDebugCommandBundle\Tests\Dummy';
        $name = 'name';

        $factory = new FilterFactory();

        $factory->register($name, $fqcn);
        $factory->createFromName($name, ['param']);
    }
} 