<?php
namespace Magenest\Movie\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\InvoiceOrder;
class ChangeOrderStatus implements ObserverInterface
{
    private $invoice;

    public function __construct(InvoiceOrder $invoice)
    {
        $this->invoice = $invoice;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getData('order');
        $orderId = $observer->getData('order_ids')[0];
        $debug = '';
        $this->invoice->execute($orderId);
    }
}
