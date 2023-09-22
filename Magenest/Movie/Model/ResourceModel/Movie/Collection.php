<?php

namespace Magenest\Movie\Model\ResourceModel\Movie;

use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'movie_id';

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
        $this->_init('Magenest\Movie\Model\Movie', 'Magenest\Movie\Model\ResourceModel\Movie');

        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
        $this->storeManager = $storeManager;
    }

    protected function _initSelect()
    {
        parent::_initSelect();

        $sql = $this->addFieldToSelect('movie_id')
            ->addFieldToSelect('name')
            ->addFieldToSelect('description')
            ->addFieldToSelect('rating')
            ->addFieldToSelect('director_id')
            ->getSelect()->join(
                'magenest_director',
                'main_table.director_id = magenest_director.director_id',
                ['director' => 'name']
            )->join(
                'magenest_movie_actor',
                'main_table.movie_id = magenest_movie_actor.movie_id',
                null
            )
            ->join(
                'magenest_actor',
                'magenest_movie_actor.actor_id = magenest_actor.actor_id',
                [
                    'actor' => new \Zend_Db_Expr('group_concat(`magenest_actor`.name)'),
                    'actor_id' => new \Zend_Db_Expr('group_concat(`magenest_actor`.actor_id)')
                ]
            )
            ->group('main_table.movie_id');
        $debug = '';
    }
}
