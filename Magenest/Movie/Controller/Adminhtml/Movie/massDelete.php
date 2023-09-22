<?php

namespace Magenest\Movie\Controller\Adminhtml\Movie;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Controller\Result\JsonFactory;
use Magenest\Movie\Model\MovieFactory;
use Magento\Framework\App\ResourceConnection;

class MassDelete extends Action
{
    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var CollectionFactory
     */
    private $movieFactory;
    private $resource;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context            $context,
        Filter             $filter,
        MovieFactory  $movieFactory,
        ResourceConnection $resource
    )
    {
        $this->filter = $filter;
        $this->movieFactory = $movieFactory;
        $this->resource = $resource;

        parent::__construct($context);
    }

    /**
     * Execute action.
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     *
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $selectedIds = $this->getRequest()->getParam('selected');
            $done = 0;
            foreach ($selectedIds as $id)
            {
                $this->movieFactory->create()->load($id)->delete();
                $connection = $this->resource->getConnection();
                $connection->delete('magenest_movie_actor',['movie_id = '.$id]);
                ++$done;
            }

            if ($done) {
                $this->messageManager->addSuccess(__('A total of %1 record(s) were modified.', $done));
            }
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Movie::movie_form');
    }
}
