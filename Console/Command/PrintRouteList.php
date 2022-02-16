<?php

declare(strict_types=1);

namespace Erybkin\RouteList\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Erybkin\RouteList\Model\RouteReader;

/**
 * Class PrintRouteList
 */
class PrintRouteList extends Command
{
    /**
     * @var RouteReader
     */
    private $routeReader;

    public function __construct(RouteReader $routeReader)
    {
        parent::__construct();

        $this->routeReader = $routeReader;
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->setName(
            'info:route:list'
        );

        $this->setDescription(
            ''
        );

        parent::configure();
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $routesCollection = $this->routeReader->getActionControllers();
        // filter by module name
        // $filteredItems = $routesCollection->getItemsByColumnValue('moduleName', 'Magento_Customer');

        $filteredItems = $routesCollection->getItemsByColumnValue('actionName', 'abstract?');
        $rows = $this->routeReader->prepareForTableRendering($filteredItems);

        $table = new Table($output);
        $table->setHeaders(['Module Name', 'Area', 'Namespace', 'Action']);
        $table->setRows($rows);

        $table->render();
    }
}
