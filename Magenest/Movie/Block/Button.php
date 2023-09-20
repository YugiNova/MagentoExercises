<?php
namespace Magenest\Movie\Block;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Backend\Block\Template\Context;

class Button extends \Magento\Config\Block\System\Config\Form\Field
{
    protected $_template = 'Magenest_Movie::button.phtml';
    protected $context;

    public function __construct(Context $context, array $data = [])
    {
        $this->context = $context;
        parent::__construct($context, $data);
    }

    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    public function refreshUrl()
    {
        $url = $this->context->getUrlBuilder()->getCurrentUrl();
        return $url;
    }
}

