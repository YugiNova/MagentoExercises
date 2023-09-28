<?php

namespace Magenest\Movie\Ui\Component\Orders\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class OddEven extends Column
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
                if($item['entity_id'] % 2 == 0){
                    $item[$this->getData('name')] = '<span class="grid-severity-notice"><span>Even</span></span>';
                }
              else{
                  $item[$this->getData('name')] = '<span class="grid-severity-critical"><span>Odd</span></span>';
              }

                $this->debug();
            }
        }
        return $dataSource;
    }
}
