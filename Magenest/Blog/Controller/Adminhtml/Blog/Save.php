<?php

namespace Magenest\Blog\Controller\Adminhtml\Blog;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Backend\Model\Auth\Session;
use Magenest\Blog\Model\BlogFactory;
use Magento\UrlRewrite\Model\UrlRewriteFactory;

class Save extends \Magento\Backend\App\Action
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
            $data = $this->getRequest()->getPostValue();
            $blogId = $data['blog_id'] ?? '';
            if (!$blogId) {
                $this->createBlog($data);
            }
            else{
                $this->updateBlog($data,$blogId);
            }
            return $this->_redirect('blog/blog/index');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->_redirect('blog/blog/form');
        }
    }

    /**
     * @param $data
     * @return void
     */
    public function createBlog($data)
    {
        $blog = $this->blogFactory->create();
        $currentAdmin = $this->adminSession->getUser()->getData('user_id');
        if($data['url_rewrite'] == "")
        {
            $blog->addData([
                'author_id' => $currentAdmin,
                'title' => $data['title'],
                'description' => $data['description'],
                'content' => $data['content'],
                'status' => $data['status']
            ])->save();
            $blog->setData('url_rewrite','blog'.$blog->getId().'.html')->save();
        }
        else
        {
            $this->checkUniqueUrlRewrite($data['url_rewrite']);
            $blog->addData([
                'author_id' => $currentAdmin,
                'title' => $data['title'],
                'description' => $data['description'],
                'content' => $data['content'],
                'url_rewrite' => $data['url_rewrite'],
                'status' => $data['status']
            ])->save();
        }
        $this->createUrlRewrite($blog->getData());
    }

    public function updateBlog($data,$blogId)
    {
        $blog = $this->blogFactory->create()->load($blogId);
        $currentAdmin = $this->adminSession->getUser()->getData('user_id');
        $this->checkUniqueUrlRewrite($data['url_rewrite'],$blogId);
        $blog->setData('author_id',$currentAdmin)
            ->setData('title',$data['title'])
            ->setData('description',$data['description'])
            ->setData('content',$data['content'])
            ->setData('url_rewrite',$data['url_rewrite'])
            ->setData('status', $data['status'])->save();
        $this->updateUrlRewrite($blog->getData());
    }

    public function createUrlRewrite($data)
    {
        $urlRewrite = $this->urlRewriteFactory->create();

        /*set current store ID */
        $urlRewrite->setStoreId(2);

        /*set 0 as this url is not created by system */
        $urlRewrite->setIsSystem(0);

        /* unique identifier - place random unique value to ID path */
        $urlRewrite->setIdPath(rand(1,1000000));

        /* set actual url path to target path field */
        $urlRewrite->setTargetPath("blog/blog/detail/id/".$data['blog_id']);

        /* set requested path which you want to create */
        $urlRewrite->setRequestPath($data['url_rewrite']);

        /* save URL rewrite rule */
        $urlRewrite->save();
    }

    public function updateUrlRewrite($data)
    {
        $urlRewrite = $this->urlRewriteFactory->create();
        /* set requested path which you want to create */
        $urlRewrite->setRequestPath($data['url_rewrite']);
    }

    public function checkUniqueUrlRewrite($url,$blogId = "")
    {
        $blogCollection = $this->blogFactory->create()->getCollection();
        $check = $blogCollection->addFieldToFilter('url_rewrite',$url)->getData();
        if(!$blogId || $check[0]['blog_id'] != $blogId)
        {
            if(count($check)>0)
            {
                $this->_redirect('*/*/*');
                throw new \Magento\Framework\Exception\LocalizedException(__("Url rewrite string is used!"));
            }
        }
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Blog::blog_form');
    }
}
