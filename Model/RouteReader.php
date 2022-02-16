<?php

namespace Erybkin\RouteList\Model;

use Erybkin\RouteList\Model\Parser;
use Magento\Framework\App\Router\ActionList;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Data\Collection;
use Magento\Framework\Data\CollectionFactory;
use Magento\Framework\Module\Dir\Reader;

// Magento\Framework\App\Route\Config::_getRoutes('frontend') - protected
// returns all frontend modules route front names
// Magento\Framework\Module\Dir\Reader::getActionFiles() - public
// Returns all action contollers files
// Magento\Framework\App\Router\ActionList::get(string moduleName, string area, string namespace, string actionName) - public
// Returns
/**
 * Class RouteReader
 */
class RouteReader
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var ActionList
     */
    private $actionList;

    /**
     * @var DataObjectFactory
     */
    private $dataObjectFactory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var Parser
     */
    private $parser;

    public function __construct(
        Reader $reader,
        ActionList $actionList,
        DataObjectFactory $dataObjectFactory,
        CollectionFactory $collectionFactory,
        Parser $parser
    ) {
        $this->reader     = $reader;
        $this->actionList = $actionList;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->collectionFactory = $collectionFactory;
        $this->parser = $parser;
    }

    public function getActionControllers(): Collection
    {
        $actionControllers = $this->reader->getActionFiles();

        $collection = $this->collectionFactory->create();

        foreach ($actionControllers as $controller) {
            $item = $this->dataObjectFactory->create();

            $item->setData(
                $this->parser->parseControllerPath($controller)
            );

            $collection->addItem($item);
        }

        return $collection;
    }

    public function prepareForTableRendering(array $items): array
    {
        $result = [];

        /** @var DataObject $item */
        foreach ($items as $item) {
            $result[] = $item->getData();
        }

        return $result;
    }
}
