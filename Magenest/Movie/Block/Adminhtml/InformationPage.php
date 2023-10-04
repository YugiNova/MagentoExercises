<?php

namespace Magenest\Movie\Block\Adminhtml;

use Magento\Framework\View\Element\Template;
use \Magento\Framework\Module\FullModuleList;
use \Magento\Customer\Model\CustomerFactory;
use \Magento\Catalog\Model\ProductFactory;
use Magento\Sales\Model\OrderFactory;
use \Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use \Magento\Sales\Model\Order\InvoiceFactory;
use \Magento\Sales\Model\ResourceModel\Order\Creditmemo\CollectionFactory as CreditmemoCollectionFactory;

class InformationPage extends Template
{
    private $moduleList;
    private $coreModules;
    private $customModules;
    private $customerFactory;

    private $orderCollectionFactory;

    private $invoiceFactory;

    private $productFactory;

    private $creditmemoCollectionFactory;

    public function __construct(
        Template\Context  $context,
        FullModuleList    $moduleList,
        CustomerFactory   $customerFactory,
        ProductFactory    $productFactory,
        CollectionFactory $orderCollectionFactory,
        InvoiceFactory    $invoiceFactory,
        CreditmemoCollectionFactory $creditmemoCollectionFactory,
        array             $data = [])
    {
        $this->moduleList = $moduleList;
        $this->customerFactory = $customerFactory;
        $this->productFactory = $productFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->invoiceFactory = $invoiceFactory;
        $this->creditmemoCollectionFactory = $creditmemoCollectionFactory;
        parent::__construct($context, $data);
        $debugger = '';
    }

    public function getModuleList()
    {
        $list = $this->moduleList->getAll();
        $debug = '';
        foreach ($list as $module) {
            if (str_contains($module['name'], 'Magento_')) {
                $this->coreModules[] = $module;
            } else {
                $this->customModules[] = $module;
            }
        }

        return count($list);
    }

    public function getCoreModuleCount()
    {
        return count($this->coreModules);
    }

    public function getCustomModuleCount()
    {
        return count($this->customModules);
    }

    public function getCustomerCount()
    {
        $customers = $this->customerFactory->create()->getCollection();
        return $customers->getSize();
    }

    public function getProductCount()
    {
        $products = $this->productFactory->create()->getCollection();
        return $products->getSize();
    }

    public function getOrderCount()
    {
        $orders = $this->orderCollectionFactory->create();
        return $orders->getSize();
    }

    public  function getInvoiceCount()
    {
        $invoices = $this->invoiceFactory->create()->getCollection();
        return $invoices->getSize();
    }

    public  function getCreditmemoCount()
    {
        //$invoices = $this->creditmemoCollectionFactory->create();
        $invoices = $this->orderCollectionFactory->create()
                    ->addFieldToFilter('status',\Magento\Sales\Model\Order::STATE_CLOSED);
        return $invoices->getSize();
    }
}
