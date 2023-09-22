<?php

namespace Magenest\Movie\Controller\Adminhtml\Movie;

use Magenest\Movie\Model\MovieFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\ResourceConnection;

class Delete extends Action
{
    protected $resultPageFactory;
    protected $movieFactory;
    protected $resource;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        MovieFactory $movieFactory,
        ResourceConnection $resource
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->movieFactory = $movieFactory;
        $this->resource = $resource;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirectFactory = $this->resultRedirectFactory->create();
        try {
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model = $this->movieFactory->create()->load($id);
                if ($model->getId()) {
                    $model->delete();
                    $connection = $this->resource->getConnection();
                    $connection->delete(
                        'magenest_movie_actor',
                        ['movie_id = '.$id]
                    );
                    $this->messageManager->addSuccessMessage(__("Movie Delete Successfully."));
                } else {
                    $this->messageManager->addErrorMessage(__("Something went wrong, Please try again."));
                }
            } else {
                $this->messageManager->addErrorMessage(__("Something went wrong, Please try again."));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e, __("We can't delete record, Please try again."));
        }
        return $resultRedirectFactory->setPath('*/*/index');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Movie::movie_form');
    }
}
