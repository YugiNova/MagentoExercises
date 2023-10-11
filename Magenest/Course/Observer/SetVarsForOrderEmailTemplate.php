<?php

namespace Magenest\Course\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\ResourceConnection;

class SetVarsForOrderEmailTemplate implements ObserverInterface
{
    private $resource;

    public function __construct(
        ResourceConnection $resource
    )
    {
        $this->resource = $resource;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $data = $observer->getData('transportObject');
        $order = $data->getData('order');
        $orderItems = $order['items'];
//        foreach ($orderItems as $item)
//        {
//            $productId = $item->getId();
//            $productDocumentsLink = $this->resource->getConnection()
//                ->fetchAll('select * from course_files where product_id = ?',[$productId]);
//            if(count($productDocumentsLink) > 0)
//            {
//
//            }
//        }

        $debug = '';
    }
}
