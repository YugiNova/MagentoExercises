<?php
namespace Magenest\Blog\Model\Blog;

class Backend extends \Magento\Framework\App\Config\Value
{
    /**
     * Cron string path constant
     */
    const CRON_STRING_PATH = 'crontab/index/jobs/cron_add_blog/schedule/cron_expr';

    /**
     * Cron model path constant
     */
    const CRON_MODEL_PATH = 'crontab/index/jobs/cron_add_blog/run/model';

    /**
     * @var \Magento\Framework\App\Config\ValueFactory
     */
    protected $_configValueFactory;

    /**
     * @var string
     */
    protected $_runModelPath = '';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\App\Config\ValueFactory $configValueFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param string $runModelPath
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Config\ValueFactory $configValueFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        $runModelPath = '',
        array $data = []
    ) {
        $this->_runModelPath = $runModelPath;
        $this->_configValueFactory = $configValueFactory;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * @inheritdoc
     *
     * @return $this
     * @throws \Exception
     */
    public function afterSave()
    {
        $cronExprString = $this->getData('groups/blog_cronjob/fields/blog_cronjob_schedule/value');

        try {
            $configPath = $this->_configValueFactory->create()->load(
                self::CRON_STRING_PATH,
                'path'
            );
            $configPath->setValue(
                $cronExprString
            )->setPath(
                self::CRON_STRING_PATH
            )->save();

            $configModel = $this->_configValueFactory->create()->load(
                self::CRON_MODEL_PATH,
                'path'
            );

            $configModel->setValue(
                $this->_runModelPath
            )->setPath(
                self::CRON_MODEL_PATH
            )->save();
        } catch (\Exception $e) {
            throw new \Exception(__('We can\'t save the cron expression.'));
        }

        return parent::afterSave();
    }
}
