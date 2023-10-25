<?php

namespace Magenest\Blog\Block\Blog;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\View\Element\Template;
use Magenest\Blog\Model\BlogFactory;
use Magento\Checkout\Model\Session as CheckoutSession;

class BlogMenu extends Template
{
    public function __construct(
        Template\Context $context,
        BlogFactory $blogFactory,
        CollectionFactory $productCollectionFactory,
        CheckoutSession $checkoutSession,
        array            $data = [])
    {
        $this->checkoutSession = $checkoutSession;
        $this->blogFactory = $blogFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $data);
    }
}
