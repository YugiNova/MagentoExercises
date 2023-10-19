<?php

namespace Magenest\Blog\Model\ResourceModel;

use Magenest\Blog\Api\BlogInterface;
use Magenest\Blog\Model\BlogFactory;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Authorization\Model\UserContextInterface;
use Magenest\Blog\Controller\Adminhtml\Blog\Save;
use Magento\UrlRewrite\Model\UrlRewriteFactory;

class BlogRepository implements BlogInterface
{
    protected $blogFactory;
    private $request;

    private $userContext;

    private $urlRewriteFactory;

    public function __construct(
        BlogFactory          $blogFactory,
        Request              $request,
        UserContextInterface $userContext,
        UrlRewriteFactory    $urlRewriteFactory
    )
    {
        $this->blogFactory = $blogFactory;
        $this->request = $request;
        $this->userContext = $userContext;
        $this->urlRewriteFactory = $urlRewriteFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlogs()
    {
        // TODO: Implement getBlogs() method.
        $blogs = $this->blogFactory->create()->getCollection()->getData();
        return $blogs;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlogById($id)
    {
        // TODO: Implement addBlog() method.
        $blog = $this->blogFactory->create()->load($id)->getData();
        return ['blog' => $blog];
    }

    /**
     * {@inheritdoc}
     */
    public function addBlog()
    {
        // TODO: Implement addBlog() method.
        try {
            $data = $this->request->getBodyParams();
            $this->checkUniqueUrlRewrite($data['url_rewrite']);
            $userId = $this->userContext->getUserId();
            $blog = $this->blogFactory->create();
            $blog->addData([
                'author_id' => $userId,
                'title' => $data['title'],
                'description' => $data['description'],
                'content' => $data['content'],
                'url_rewrite' => $data['url_rewrite'],
                'status' => $data['status']
            ])->save();
            if ($blog->getData('url_rewrite') == '') {
                $urlRewrite = "blog" . $blog->getId() . ".html";
                $blog->setData('url_rewrite', $urlRewrite)->save();
            }

            $this->createUrlRewrite($blog->getData());

            return ['userId' => $userId, 'blog' => $blog->getData()];
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

    }

    /**
     * Create Url Rewrite for blog
     * @param $data
     * @return void
     */
    public function createUrlRewrite($data)
    {
        $urlRewrite = $this->urlRewriteFactory->create();

        /*set current store ID */
        $urlRewrite->setStoreId(2);

        /*set 0 as this url is not created by system */
        $urlRewrite->setIsSystem(0);

        /* unique identifier - place random unique value to ID path */
        $urlRewrite->setIdPath(rand(1, 1000000));

        /* set actual url path to target path field */
        $urlRewrite->setTargetPath("blog/blog/detail/id/" . $data['blog_id']);

        /* set requested path which you want to create */
        $urlRewrite->setRequestPath($data['url_rewrite']);

        /* save URL rewrite rule */
        $urlRewrite->save();
    }

    /**
     * Check if Url rewrite is used
     * @param $url
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function checkUniqueUrlRewrite($url)
    {
        $blogCollection = $this->blogFactory->create()->getCollection();
        $check = $blogCollection->addFieldToFilter('url_rewrite', $url)->getData();
        if (count($check) > 0) {
            throw new \Magento\Framework\Exception\LocalizedException(__("Url rewrite string is used!"));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updateBlog($id)
    {
        try {
            $data = $this->request->getBodyParams();
            $this->checkUniqueUrlRewrite($data['url_rewrite']);
            $userId = $this->userContext->getUserId();
            $blog = $this->blogFactory->create()->load($id);
            $oldRequestPath = $blog->getData()['url_rewrite'];
            $blog->setData([
                'author_id' => $userId,
                'title' => $data['title'],
                'description' => $data['description'],
                'content' => $data['content'],
                'url_rewrite' => $data['url_rewrite'],
                'status' => $data['status']
            ])->save();
            $blog->setData('author_id', $userId)
                ->setData('title', $data['title'])
                ->setData('description', $data['description'])
                ->setData('content', $data['content'])
                ->setData('url_rewrite', $data['url_rewrite'])
                ->setData('status', $data['status'])->save();

            if ($blog->getData('url_rewrite') == '') {
                $urlRewrite = "blog" . $blog->getId() . ".html";
                $blog->setData('url_rewrite', $urlRewrite)->save();
            }

            $this->updateUrlRewrite($oldRequestPath,$blog->getData());

            return ['userId' => $userId, 'blog' => $blog->getData()];
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function updateUrlRewrite ($oldRequestPath,$newRequestPath)
    {
        $urlRewrite = $this->urlRewriteFactory->create();
        $urlRewriteIds = $urlRewrite->getCollection()->addFieldToFilter('request_path', $oldRequestPath)->getAllIds();
        if(count($urlRewriteIds) == 0)
        {
            foreach ($urlRewriteIds as $id) {
                 $this->urlRewriteFactory->create()->load($id)->setRequestPath($newRequestPath)->save();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteBlog($id)
    {
        // TODO: Implement addBlog() method.
        $blog = $this->blogFactory->create()->load($id);
        $this->deleteUrlReewrite($blog->getData('url_rewrite'));
        $blog->delete()->save();
        return "Delete blog successfull";
    }

    public function deleteUrlReewrite($requestPath)
    {
        $urlRewrite = $this->urlRewriteFactory->create();
        $urlRewriteIds = $urlRewrite->getCollection()->addFieldToFilter('request_path', $requestPath)->getAllIds();
        foreach ($urlRewriteIds as $id) {
            $this->urlRewriteFactory->create()->load($id)->delete()->save();
        }
    }
}
