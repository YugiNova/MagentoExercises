<?php

namespace Magenest\Blog\Block;

use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session;

class AccountType extends Template
{
    private $customerSession;

    public function __construct(
        Template\Context $context,
        Session $customerSession,
        array            $data = [])
    {
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
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
