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
        if($order->getData('total_due') == '0.0000')
        {
            $orderId = $observer->getData('order_ids')[0];
            $this->invoice->execute($orderId);
        }
        $debug = '';
    }
}
