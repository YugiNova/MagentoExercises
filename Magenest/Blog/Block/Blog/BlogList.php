<?php

namespace Magenest\Blog\Block\Blog;

use Magento\Framework\View\Element\Template;
use Magenest\Blog\Model\BlogFactory;

class BlogList extends Template
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

    public function getBlogs()
    {
        $blogs = $this->blogFactory->create()->getCollection();
        return $blogs;
    }
}
