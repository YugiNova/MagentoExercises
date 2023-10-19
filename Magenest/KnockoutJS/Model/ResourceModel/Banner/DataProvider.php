<?php
namespace Magenest\KnockoutJS\Model\ResourceModel\Banner;

use Magenest\KnockoutJS\Model\ResourceModel\Banner\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $loadedData;

    protected $storeManager;

    /**
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param \Magenest\KnockoutJS\Model\ResourceModel\Banner\CollectionFactory $bannerCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $bannerCollectionFactory,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $bannerCollectionFactory->create();
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            $this->loadedData[$model->getId()] = $model->getData();
            if ($model->getData('image_url')) {
                // replace icon to your custom field name
                $m['image_url'][0]['name'] = explode('/',$model->getData('image_url'))[1];
                $m['image_url'][0]['url'] = $this->getMediaUrl().$model->getData('image_url');
                $m['image_url'][0]['type'] = 'image';
                $fullData = $this->loadedData;
                $this->loadedData[$model->getId()] = array_merge($fullData[$model->getId()], $m);
            }
        }
        $debug = '';
        return $this->loadedData;
    }


    public function getMediaUrl()
    {
        $mediaUrl = $this->storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return 'http://local.magento.com/pub/media/';
    }
}
