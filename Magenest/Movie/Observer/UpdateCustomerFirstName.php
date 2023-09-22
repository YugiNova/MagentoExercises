<?php
namespace Magenest\Movie\Observer;
use Magento\Framework\Event\ObserverInterface;
class UpdateCustomerFirstName implements ObserverInterface
{
    protected $messageManager;

    public function __construct(\Magento\Framework\Message\ManagerInterface $messageManager)
    {
        $this->messageManager = $messageManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer = $observer->getData('customer');
        $debug = '';
        $customer->setFirstname('Magenest');
    }
}
