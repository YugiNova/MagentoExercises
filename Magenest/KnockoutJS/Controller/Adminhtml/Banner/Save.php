<?php

namespace Magenest\KnockoutJS\Controller\Adminhtml\Banner;

use Magenest\KnockoutJS\Model\Banner;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Backend\Model\Auth\Session;
use Magenest\KnockoutJS\Model\BannerFactory;
use Magenest\KnockoutJS\Model\Uploader;

class Save extends \Magento\Backend\App\Action
{
    protected $resultRedirectFactory;
    protected $scopeConfig;
    protected $context;
    protected $adminSession;
    protected $resource;
    protected $bannerFactory;

    protected $imageUploader;

    public function __construct(
        Context              $context,
        RedirectFactory      $resultRedirectFactory,
        ScopeConfigInterface $scopeConfig,
        ResourceConnection   $resource,
        Session              $adminSession,
        BannerFactory        $bannerFactory,
        Uploader             $imageUploader
    )
    {
        parent::__construct($context);
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->scopeConfig = $scopeConfig;
        $this->context = $context;
        $this->resource = $resource;
        $this->adminSession = $adminSession;
        $this->bannerFactory = $bannerFactory;
        $this->imageUploader = $imageUploader;
    }

    public function execute()
    {
        try {
            $data = $this->getRequest()->getPostValue();
            $bannerId = $this->getRequest()->getParam('banner_id');
            if (isset($bannerId)) {
                $this->updateBanner($data, $bannerId);
            } else {
                $this->createBanner($data);
            }

            return $this->_redirect('*/*/index');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->_redirect('*/*/form');
        }
    }

    /**
     * Pass Post value as data, return image path
     * @param $data
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getImagePath($data)
    {
        $imageName = $data['image_url'][0]['name'];
        $imagePath = $this->imageUploader->moveFileFromTmp($imageName);
        return $imagePath;
    }

    public function createBanner($data)
    {
        $imagePath = $this->getImagePath($data);
        $banner = $this->bannerFactory->create();
        $banner->addData([
            'enable' => $data['enable'],
            'title' => $data['title'],
            'image_url' => $imagePath
        ])->save();
    }

    public function updateBanner($data, $id)
    {


        $banner = $this->bannerFactory->create()->load($id);
        $oldImage = 'banner/' . $data['image_url'][0]['name'];
        $newImage = $banner->getData('image_url');
        if ($oldImage == $newImage)
        {
            $banner->setData('enable', $data['enable'] == "true" ? 1 : 0)
                ->setData('title', $data['title'])->save();
        } else
        {
            $imagePath = $this->getImagePath($data);
            $banner->setData('enable', $data['enable'] == "true" ? 1 : 0)
                ->setData('title', $data['title'])
                ->setData('image_url', $imagePath)->save();
        }
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Blog::blog_form');
    }
}
