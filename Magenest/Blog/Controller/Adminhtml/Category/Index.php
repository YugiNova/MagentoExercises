<?php

namespace Magenest\Blog\Controller\Adminhtml\Category;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;
    protected $resultJsonFactory;
    protected $scopeConfig;

    public function __construct(
        Context     $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
    }
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magenest_Blog::blog_menu');
        $resultPage->getConfig()->getTitle()->prepend(__('Blog Category'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Blog::blog_category');
    }
}
