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
        $baseUrl = 'http://local.magento.com/';
        $mediaUrl = $baseUrl . 'media/customer';
        return $mediaUrl;
    }
    public function getCustomerAvatar()
    {
        $avatarPath = $this->currentCustomer->getCustomer()->getCustomAttribute('avatar');
        $avatarUrl =  $avatarPath ? $this->getCusomterMeidaUrl() . $avatarPath->getValue() : "";

        return $avatarUrl;
    }

    public function getCurrentCustomer()
    {
        $customer = $this->currentCustomer->getCustomer();
        return $customer;
    }

}

