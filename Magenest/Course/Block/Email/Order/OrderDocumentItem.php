<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\Course\Block\Email\Order;

use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\Order\Item as OrderItem;
use Magento\Framework\App\ResourceConnection;

/**
 * Sales Order Email items default renderer
 *
 * @api
 * @author     Magento Core Team <core@magentocommerce.com>
 * @since 100.0.2
 */
class OrderDocumentItem extends \Magento\Framework\View\Element\Template
{
    private $resource;

    public function __construct(Template\Context $context, array $data = [], ResourceConnection $resource)
    {
        $this->resource = $resource;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve current order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->getItem()->getOrder();
    }

    /**
     * Returns array of Item options
     *
     * @return array
     */
    public function getItemOptions()
    {
        $result = [];
        if ($options = $this->getItem()->getProductOptions()) {
            if (isset($options['options'])) {
                $result[] = $options['options'];
            }
            if (isset($options['additional_options'])) {
                $result[] = $options['additional_options'];
            }
            if (isset($options['attributes_info'])) {
                $result[] = $options['attributes_info'];
            }
        }

        return array_merge([], ...$result);
    }

    /**
     * Formats the value in HTML
     *
     * @param string|array $value
     * @return string
     */
    public function getValueHtml($value)
    {
        if (is_array($value)) {
            return sprintf('%d', $value['qty'])
                . ' x ' . $this->escapeHtml($value['title'])
                . " " . $this->getItem()->getOrder()->formatPrice($value['price']);
        } else {
            return $this->escapeHtml($value);
        }
    }

    /**
     * Returns Product SKU for Item provided
     *
     * @param OrderItem $item
     * @return mixed
     */
    public function getSku($item)
    {
        if ($item->getProductOptionByCode('simple_sku')) {
            return $item->getProductOptionByCode('simple_sku');
        } else {
            return $item->getSku();
        }
    }

    /**
     * Return product additional information block
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    public function getProductAdditionalInformationBlock()
    {
        return $this->getLayout()->getBlock('additional.product.info');
    }

    /**
     * Get the html for item price
     *
     * @param OrderItem $item
     * @return string
     */
    public function getItemPrice(OrderItem $item)
    {
        $block = $this->getLayout()->getBlock('item_price');
        $block->setItem($item);
        return $block->toHtml();
    }

    public function getItemDocuments(OrderItem $item)
    {
        $connection = $this->resource->getConnection();
        $documents = $connection->fetchAll('select * from course_files where product_id = ?',[$item['product_id']]);
        $result = [];
        foreach ($documents as $document)
        {
            $documentUrl = $this->_urlBuilder->getBaseUrl() . 'pub/media/' . $document['file_url'];
            $result[] = [
                'url' => $documentUrl,
                'label' => $document['file_label']
            ];
        }
        return $result;
    }
}
