<?php
namespace Magenest\Course\Controller\Adminhtml\Product;

use Magento\Framework\Controller\ResultFactory;

/**
 * Adminhtml Category Image Upload Controller
 */
class CourseFile extends \Magento\Backend\App\Action
{
    /**
     * @var \Magenest\Course\Model\Uploader
     */
    protected $imageUploader;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magenest\Course\Model\Uploader $imageUploader
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magenest\Course\Model\Uploader $imageUploader,
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    /**
     * Check admin permissions for this controller
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_Sales::create');
    }

    /**
     * Upload file controller action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $files = $this->getRequest()->getFiles('product')['course-files']['dynamic_row'];
            foreach ($files as $file)
            {
                $_FILES = $file;
            }
            $result = $this->imageUploader->saveFileToTmpDir('file_url');
            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
