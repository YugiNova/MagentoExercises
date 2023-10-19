<?php
namespace Magenest\KnockoutJS\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\Serializer\Json;

class ColorSwitch extends Template
{
    /**
     * @var array|\Magento\Checkout\Block\Checkout\LayoutProcessorInterface[]
     */
    protected $layoutProcessors;

    protected $configData;

    protected $json;

    public function __construct(
        Template\Context $context,
        ScopeConfigInterface $configData,
        Json $json,
        array $data = []
    ) {
        $this->configData = $configData;
        $this->json = $json;
        parent::__construct($context, $data);
    }

    public function getColorOptions()
    {
        $options = $this->configData->getValue('background_color/background_color_config/background_color_options');
        $options = $this->json->unserialize($options);
        return $options;
    }

    public function getColorOptionsJson()
    {
        $options = $this->configData->getValue('background_color/background_color_config/background_color_options');
        return $options;
    }


}
