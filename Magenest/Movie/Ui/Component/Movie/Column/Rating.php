<?php

namespace Magenest\Movie\Ui\Component\Movie\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Rating extends Column
{
    protected $urlBuilder;

    public function __construct(
        ContextInterface   $context,
        UiComponentFactory $HelloWorldFactory,
        UrlInterface       $urlBuilder,
        array              $components = [],
        array              $data = []
    )
    {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $HelloWorldFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $this->debug();
                if (isset($item['movie_id'])) {
                    $tmp = $item[$this->getData('name')];
                    $tmp = round($tmp / 100000 * 10);
                    $rating = '';
                    for ($i = 1; $i <= 10; $i++) {
                        if ($i <= $tmp) {
                            $rating .= '<span class="fa fa-star custom-star checked"></span>';
                        } else {
                            $rating .= '<span class="fa fa-star custom-star"></span>';
                        }
                    }

                    $item[$this->getData('name')] = $rating;
                }
            }
        }
        return $dataSource;
    }
}
