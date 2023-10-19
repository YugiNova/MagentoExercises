<?php

namespace Magenest\KnockoutJS\Ui\Banner\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Actions extends Column
{

    const URL_PATH_EDIT = 'knockout/banner/form';
    const URL_PATH_DELETE = 'knockout/banner/form';

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
                $this->debug();
                if (isset($item['banner_id'])) {
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_EDIT,
                                [
                                    'id' => $item['banner_id'],
                                ]
                            ),
                            'label' => __('Edit'),
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                [
                                    'id' => $item['banner_id'],
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete Record ?'),
                                'message' => __('Are you sure you wan\'t to delete a record?'),
                            ],
                        ],
                    ];
                }
            }
        }
        return $dataSource;
    }
}
