<?php

namespace Magenest\KnockoutJS\Block;

use Magento\Framework\View\Element\Template;
use Magenest\KnockoutJS\Model\BannerFactory;

class AsideBanner extends Template
{
    private $bannerFactory;

    public function __construct(
        Template\Context $context,
        BannerFactory    $bannerFactory,
        array            $data = []
    )
    {
        $this->bannerFactory = $bannerFactory;
        parent::__construct($context, $data);
    }

    public function getBanners()
    {
        $banners = $this->bannerFactory->create()->getCollection()->getData();
        $result = [];
        foreach ($banners as $banner) {
            if ($banner['enable'] == 1) {
                $result[] = "http://local.magento.com/pub/media/" . $banner['image_url'];
            }
        }
        return $result;
    }
}
