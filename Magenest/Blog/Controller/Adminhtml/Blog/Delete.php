<?php

namespace Magenest\Blog\Controller\Adminhtml\Blog;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Backend\Model\Auth\Session;
use Magenest\Blog\Model\BlogFactory;
use Magento\UrlRewrite\Model\UrlRewriteFactory;

class Delete extends \Magento\Backend\App\Action
{
    protected $resultRedirectFactory;
    protected $scopeConfig;
    protected $context;
    protected $adminSession;
    protected $resource;
    protected $blogFactory;
    protected $urlRewriteFactory;

    public function __construct(
        Context              $context,
        RedirectFactory      $resultRedirectFactory,
        ScopeConfigInterface $scopeConfig,
        ResourceConnection   $resource,
        Session              $adminSession,
        BlogFactory $blogFactory,
        UrlRewriteFactory $urlRewriteFactory
    )
    {
        parent::__construct($context);
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->scopeConfig = $scopeConfig;
        $this->context = $context;
        $this->resource = $resource;
        $this->adminSession = $adminSession;
        $this->blogFactory = $blogFactory;
        $this->urlRewriteFactory = $urlRewriteFactory;
    }

    public function execute()
    {
        try {
            $blogId = $this->getRequest()->getParam('id');
            $blog = $this->blogFactory->create()->load($blogId);
            $urlRewrite = $this->urlRewriteFactory->create();
            $urlRewriteIds = $urlRewrite->getCollection()->addFieldToFilter('request_path', $blog->getData('url_rewrite'))->getAllIds();
            foreach ($urlRewriteIds as $id) {
                $this->urlRewriteFactory->create()->load($id)->delete()->save();
            }
            $blog->delete()->save();
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $this->_redirect('blog/blog/index');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Blog::blog_form');
    }
}
