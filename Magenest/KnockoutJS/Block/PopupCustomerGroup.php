<?php
namespace Magenest\KnockoutJS\Block;

use Magento\Framework\View\Element\Template;

class PopupCustomerGroup extends Template
{
    /**
     * @var array|\Magento\Checkout\Block\Checkout\LayoutProcessorInterface[]
     */
    protected $layoutProcessors;

    protected $resourceConnection;

    protected $customerSesstion;

    public function __construct(
        Template\Context $context,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->resourceConnection = $resourceConnection;
        $this->customerSesstion = $customerSession;
        $this->jsLayout = isset($data['jsLayout']) && is_array($data['jsLayout']) ? $data['jsLayout'] : [];
    }

    public function getPopupContent()
    {
        $customerGroupId = $this->customerSesstion->getCustomer()->getGroupId();
        $connection = $this->resourceConnection->getConnection();
        $popups = $connection->fetchAll('select * from magenest_popup where customer_group_id = ?',[$customerGroupId]);
        return $popups[0]['content'];
    }
}
