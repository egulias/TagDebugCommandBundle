<?php

/**
 * This file is part of TagDebugCommandBundle
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Egulias\TagDebugCommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerDebugCommand;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Egulias\TagDebug\Tag\TagFetcher;
use Egulias\TagDebug\Tag\FilterList;
use Egulias\TagDebug\Tag\Tag;

/**
 * TagCommand
 *
 * @author Eduardo Gulias <me@egulias.com>
 */
class TagCommand extends ContainerDebugCommand
{
    /**
     * {@inherit}
     */
    protected function configure()
    {
        $this->setDefinition(
            array(
                new InputOption('filter', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'filter-name = (param1, param2) to use'),
                new InputOption(
                    'show-private',
                    null,
                    InputOption::VALUE_NONE,
                    'Use to show public *and* private services listeners'
                ),
            )
        )
        ->setName('container:tag-debug')
        ->setDescription('Displays current tagged services for an application')
        ->setHelp(
            <<<EOF
The <info>container:tag-debug</info> command displays all tagged <comment>public</comment>
services defined.
EOF
        );
    }

    /**
     * {@inherit}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $options = array(
            'show-private' => $input->getOption('show-private'),
            'filters' => $input->getOption('filter')
        );

        $this->outputTags($output, $options);
    }

    protected function outputTags(OutputInterface $output, $options = array())
    {
        $filtersMap = ['name' => 'Egulias\TagDebug\Tag\Filter\Name'];

        $filters = new FilterList();
        foreach ($options['filters'] as $filterName) {
            $args = explode('=', $filterName);
            $const = $args[1];
            $const = explode(',', $const);
            $filter = new $filtersMap[$args[0]]($const[0]);

            $filters->append($filter);
        }

        $fetcher = new TagFetcher($this->getContainerBuilder());
        $tags = $fetcher->fetch($filters);

        $label = '<comment>Public</comment> tagged services';

        if ($options['show-private']) {
            $label = '<comment>Public</comment> and <comment>private</comment> tagged services';
        }

        $output->writeln($this->getHelper('formatter')->formatSection('container', $label));

        $table = $this->getHelperSet()->get('table');
        $table->setHeaders(array('Service', 'Tag', 'Attributes'));
        $table->setCellRowFormat('<fg=white>%s</fg=white>');

        $this->addTagsToTable($tags, $table);

        $table->render($output);
    }

    protected function addTagsToTable($tags, TableHelper $table)
    {
        foreach ($tags as $services) {
            $row = array();
            foreach ($services as $id => $tag) {
                $row[] = $id;
                $row[] = $tag->getName();
                $row[] = $this->getTagAttributes($tag);
                $table->addRow($row);
            }
        }
    }

    protected function getTagAttributes(Tag $tag)
    {
        $attributes = '';
        foreach ($tag->getAttributes() as $name => $value) {
            $attributes .= $name . ' => ' . $value . PHP_EOL;
        }

        return $attributes;
    }
}