<?php
namespace Magenest\Course\Plugin;

class ProductFormGeneralPrepare
{
    public function afterModifyMeta($subject,$meta)
    {
        $meta['course-date']['arguments']['data']['config']['label'] = 'Course';
        $meta['course-date']['arguments']['data']['config']['collapsible'] = true;
        $meta['course-date']['arguments']['data']['config']['opened'] = true;
        return $meta;
    }
}
