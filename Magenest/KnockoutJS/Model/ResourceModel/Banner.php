<?php

namespace Magenest\KnockoutJS\Model\ResourceModel;
class Banner extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init('magenest_banner','banner_id');
    }
}
