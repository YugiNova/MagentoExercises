<?php
namespace Magenest\Blog\Block\Widget\Banner;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Customer\Model\Session;

class BannerWidget extends Template implements BlockInterface
{
    protected $_template = "banner.phtml";

    private $customerSession;

    public function __construct (
        Template\Context $context,
        Session $customerSession,
        array $data = []
    )
    {
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    public function getBannerUrl()
    {
        $uploaderData = $this->getData('banner_uploader');
        if (preg_match('/(___directive\/)([a-zA-Z0-9,_-]+)/', $uploaderData, $matches)) {
            $directive = base64_decode(strtr($matches[2], '-_,', '+/='));
            $uploaderData = str_replace(['{{media url="', '"}}'], ['', ''], $directive);
        }
        return "http://local.magento.com/pub/media/" . $uploaderData;
    }

    public function getCurrentCustomer()
    {
        $customer = $this->customerSession->getCustomer();
        return $customer;
    }

    public function getCustomerIsB2b()
    {
        $isB2b = $this->getCurrentCustomer()->getData('isB2B');
        return $isB2b;
    }
}

