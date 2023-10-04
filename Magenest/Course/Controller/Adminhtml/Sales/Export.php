<?php

namespace Magenest\Course\Controller\Adminhtml\Sales;

use Magento\Backend\App\Action\Context;
use Magento\Sales\Model\OrderFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\File\Csv;
use Magento\Framework\App\Response\Http\FileFactory;

class Export extends \Magento\Backend\App\Action
{
    private $orderFactory;
    private $directoryList;
    private $csvProcessor;
    private $filename;

    private $fileFactory;

    public function __construct(
        Context      $context,
        OrderFactory $orderFactory,
        DirectoryList $directoryList,
        Csv $csvProcessor,
        FileFactory $fileFactory,
    )
    {
        parent::__construct($context);
        $this->orderFactory = $orderFactory;
        $this->directoryList = $directoryList;
        $this->csvProcessor = $csvProcessor;
        $this->fileFactory = $fileFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        $this->prepareCSV();

        $this->fileFactory->create(
            $this->filename,
            [
                'type'  => "filename",
                'value' => $this->filename,
                'rm'    => true,
            ],
            DirectoryList::MEDIA,
            'text/csv',
            null
        );

        return $this->_redirect('sales/order/index');
    }

    /**
     * @return void
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function prepareCSV()
    {
        $this->filename = date_default_timezone_get().'_sales_order_items.csv';
        $filePath =  $this->directoryList->getPath(DirectoryList::MEDIA) . "/" . $this->filename;
        $this->csvProcessor->setEnclosure('"')
            ->setDelimiter(',')
            ->saveData($filePath, $this->prepareContent());
    }

    /**
     * @return array
     */
    protected function prepareContent():array
    {
        $orderIds = $this->orderFactory->create()->getCollection()
            ->getAllIds();
        $orderItems = [];
        $content[] = [
            'Order ID',
            'Product ID',
            'Customer name',
            'Customer email',
            'Product name',
            'Product Price',
            'Product type',
            'Product SKU',
            'Created At',
            'Updated At',
        ];
        foreach ($orderIds as $orderId) {
            $order = $this->orderFactory->create()->load($orderId);
            $orderItems = $order->getItems();

            foreach ($orderItems as $item) {
                $content[] = [
                    $orderId,
                    $item->getProductId(),
                    $order->getCustomerName(),
                    $order->getCustomerEmail(),
                    $item->getName(),
                    $item->getPrice(),
                    $item->getProductType(),
                    $item->getSku(),
                    $item->getCreatedAt(),
                    $item->getUpdatedAt(),
                ];
            }
        }

        return $content;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_Sales::create');
    }
}
