<?php

namespace Magenest\KnockoutJS\Ui\Banner\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Image extends Column
{
    protected $urlBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $HelloWorldFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $HelloWorldFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['banner_id'])) {
                    $data = $item[$this->getData('name')];
                    $item[$this->getData('name')] = "<img src='http://local.magento.com/pub/media/$data' width='100px'/>";
                    $this->debug();
                }
            }
        }
        return $dataSource;
    }
}
