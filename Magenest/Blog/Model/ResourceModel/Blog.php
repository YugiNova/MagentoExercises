<?php

namespace Magenest\Blog\Model\ResourceModel;
class Blog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init('magenest_blog','blog_id');
    }
}
