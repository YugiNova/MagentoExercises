<?php

namespace Magenest\KnockoutJS\Model\ResourceModel\Banner;

use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'banner_id';

    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface        $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface       $eventManager,
        StoreManagerInterface  $storeManager,
        AdapterInterface       $connection = null,
        AbstractDb             $resource = null
    )
    {
        $this->_init('Magenest\KnockoutJS\Model\Banner','Magenest\KnockoutJS\Model\ResourceModel\Banner');

        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
        $this->storeManager = $storeManager;
    }
}
