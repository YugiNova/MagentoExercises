<?php
namespace Magenest\Course\Block\Customer;

use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\CustomerFactory;

class Avatar extends Template
{
    private $customerFactory;

    public function __construct(
        CustomerFactory $customerFactory,
        Template\Context $context,
        array $data = []
    )
    {
        $this->customerFactory = $customerFactory;
        parent::__construct($context, $data);
    }

    /**
     * Get Customer model object
     * @return \Magento\Customer\Model\Customer
     */
    public function getCustomer()
    {
        $customerId = $this->getRequest()->getParam('id');
        $customer = $this->customerFactory->create()->load($customerId);
        return $customer;
    }

    /**
     * Get Media url
     * @return string
     */
    private function getCusomterMeidaUrl()
    {
        $baseUrl = $this->_urlBuilder->getBaseUrl();
        $mediaUrl = $baseUrl . 'media/customer';
        return $mediaUrl;
    }

    /**
     * Get Customer avatar url
     * @return string
     */
    public function getCustomerAvatar()
    {
        $avatarPath = $this->getCustomer()->getData('avatar');
        $avatarUrl = $this->getCusomterMeidaUrl() . $avatarPath;

        return $avatarUrl;
    }
}
