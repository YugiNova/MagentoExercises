<?php

namespace Magenest\Blog\Block\Blog;

use Magento\Framework\View\Element\Template;
use Magenest\Blog\Model\BlogFactory;

class BlogDetail extends Template
{
    private $blogFactory;
    public function __construct(
        Template\Context $context,
        BlogFactory $blogFactory,
        array            $data = [])
    {
        $this->blogFactory = $blogFactory;
        parent::__construct($context, $data);
    }

    public function getBlog()
    {
        $blogId = $this->getRequest()->getParam('id');
        $blog = $this->blogFactory->create()->load($blogId)->getCollection()->addFieldToFilter('blog_id',$blogId)->getFirstItem();
        return $blog;
    }
}
