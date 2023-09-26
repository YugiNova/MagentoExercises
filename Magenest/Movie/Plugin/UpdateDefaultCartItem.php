<?php

namespace Magenest\Movie\Plugin;

use Magento\Checkout\CustomerData\DefaultItem;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Catalog\Model\ProductFactory;

class UpdateDefaultCartItem
{
    private $configurableProduct;
    private $product;

    public function __construct(Configurable $configurableProduct, ProductFactory $product)
    {
        $this->configurableProduct = $configurableProduct;
        $this->product = $product;
    }
    public function afterGetItemData(DefaultItem $subject,$result)
    {
        if($result['product_type'] == Configurable::TYPE_CODE)
        {
            $attribute = [$result['options'][0]['option_id'] => $result['options'][0]['option_value']];
            $product = $this->product->create()->load($result['product_id']);
            $simpleProduct = $this->configurableProduct->getProductByAttributes(
                $attribute,
                $product
            );
            $productName = $simpleProduct->getName();
            $result['product_name'] = $productName;
        }
        return $result;
    }
}
