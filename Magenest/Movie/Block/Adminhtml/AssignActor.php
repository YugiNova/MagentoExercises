<?php
namespace Magenest\Movie\Block\Adminhtml;

class AssignActor extends \Magento\Backend\Block\Widget\Grid\Container
{

    protected function _construct()
    {
        $this->_controller = 'adminhtml_assignactor';
        $this->_blockGroup = 'Magenest_Movie';
        parent::_construct();
    }
}
