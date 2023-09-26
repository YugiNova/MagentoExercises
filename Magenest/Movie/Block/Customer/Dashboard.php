<?php

namespace Magenest\Movie\Block\Customer;

use Magento\Framework\View\Element\Template;
use Magento\Customer\Helper\Session\CurrentCustomer;

class Dashboard extends Template
{
    private $currentCustomer;

    public function __construct(
        Template\Context $context,
        CurrentCustomer $currentCustomer,
        array            $data = [],
    )
    {
        $this->currentCustomer = $currentCustomer;
        parent::__construct($context, $data);
    }

    public function getCusomterMeidaUrl()
    {
        $baseUrl = $this->_urlBuilder->getBaseUrl();
        $mediaUrl = $baseUrl . 'media/customer';
        return $mediaUrl;
    }
    public function getCustomerAvatar()
    {
        $avatarPath = $this->currentCustomer->getCustomer()->getCustomAttribute('avatar')->getValue();
        $avatarUrl = $this->getCusomterMeidaUrl() . $avatarPath;

        return $avatarUrl;
    }

    public function getCurrentCustomer()
    {
        $customer = $this->currentCustomer->getCustomer();
        return $customer;
    }

}

