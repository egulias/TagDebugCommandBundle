<?php

/**
 * This file is part of TagDebugCommandBundle
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Egulias\TagDebugCommandBundle\Test\Command;

use PHPUnit_Framework_TestCase;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

use Egulias\TagDebugCommandBundle\Command\TagCommand;

/**
 * TagCommand test
 *
 * @author Eduardo Gulias <me@egulias.com>
 */
class ListenersCommandTest extends PHPUnit_Framework_TestCase
{
    protected $application;

    public function setUp()
    {
        $params = new ParameterBag(array('debug.container.dump' => __DIR__ . '/../appDevDebugProjectContainer.xml'));
        $container = new Container($params);
        $kernel = $this->getMockForAbstractClass('Symfony\Component\HttpKernel\KernelInterface');
        $kernel->expects($this->any())->method('isDebug')->will($this->returnValue(true));
        $kernel->expects($this->any())->method('getContainer')->will($this->returnValue($container));
        $this->application = new Application($kernel);
        $this->application->add(new TagCommand());
    }

    public function testBaseCommand()
    {
        $display = $this->executeCommand(array());

        $this->assertRegExp('/Service/', $display);
        $this->assertRegExp('/Tag/', $display);
        $this->assertRegExp('/Attributes/', $display);
    }

    public function testShowPrivate()
    {
        $display = $this->executeCommand(array('--show-private' => null));

        $this->assertNotRegExp('/\|private\|/', $display);
    }

    public function testUseOneFilter()
    {
        $display = $this->executeCommand(array('--filter' => array('name=custom.tag_name')));

        $this->assertNotRegExp('/kernel/', $display);
    }

    private function executeCommand(array $options)
    {
        $command = $this->application->find('container:tag-debug');
        $commandTester = new CommandTester($command);
        $default = array('command' => $command->getName());
        $options = array_merge($default, $options);
        $commandTester->execute($options);

        return $commandTester->getDisplay();
    }
}
