<?php

namespace Magenest\Course\Ui\DataProvider\Product\Form\Modifier;

use Magento\Framework\Stdlib\ArrayManager;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;

class CourseTimeUpdate extends AbstractModifier
{
    /**#@+
     * Field names
     */
    const COURSE_START = 'course_start_time';
    const COURSE_END = 'course_end_time';
    /**#@-*/

    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    /**
     * @param ArrayManager $arrayManager
     */
    public function __construct(ArrayManager $arrayManager)
    {
        $this->arrayManager = $arrayManager;
    }

    /**
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        $meta = $this->customizeDateRangeField($meta);
        $meta = $this->changeOrderCourseTimeGroup($meta);
        return $meta;
    }

    /**
     * @param array $data
     * @return array
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    public function changeOrderCourseTimeGroup(array $meta)
    {
        $meta = $this->arrayManager->merge(
            'course-date/arguments/data/config',
            $meta,
            [
                'sortOrder' => 0,
                'collapsible' => true
            ]
        );

        $meta = $this->arrayManager->merge(
            'product-details/arguments/data/config',
            $meta,
            [
                'sortOrder' => 10,
                'opened' => true
            ]
        );

        return $meta;
    }

    /**
     * @param array $meta
     * @return array$this->getGroupCodeByField($meta, self::COURSE_START)
     */
    protected function customizeDateRangeField(array $meta)
    {
        if ($this->getGroupCodeByField($meta, self::COURSE_START)
            !== $this->getGroupCodeByField($meta, self::COURSE_END)
        ) {
            return $meta;
        }

        $fromFieldPath = $this->arrayManager->findPath(self::COURSE_START, $meta, null, 'children');
        $toFieldPath = $this->arrayManager->findPath(self::COURSE_END, $meta, null, 'children');
        $fromContainerPath = $this->arrayManager->slicePath($fromFieldPath, 0, -2);
        $toContainerPath = $this->arrayManager->slicePath($toFieldPath, 0, -2);

        $meta = $this->arrayManager->merge(
            $fromFieldPath . self::META_CONFIG_PATH,
            $meta,
            [
                'label' => __('Course active time'),
                'additionalClasses' => 'admin__field-date',
                'sortOrder' => 1,
                'required' => '0',
                'id' => 'course_start_time',
                'component' => 'Magenest_Course/js/custom-date',
                'options' => [
                    'showsTime' => true,
                ]
            ]
        );
        $meta = $this->arrayManager->merge(
            $toFieldPath . self::META_CONFIG_PATH,
            $meta,
            [
                'label' => __('To'),
                'scopeLabel' => null,
                'additionalClasses' => 'admin__field-date',
                'sortOrder' => 2,
                'id' => 'course_end_time',
                'required' => '0',
                'component' => 'Magenest_Course/js/custom-date',
                'options' => [
                    'showsTime' => true,
                ]
            ]
        );
        $meta = $this->arrayManager->merge(
            $fromContainerPath . self::META_CONFIG_PATH,
            $meta,
            [
                'label' => false,
                'required' => false,
                'additionalClasses' => 'admin__control-grouped-date',
                'breakLine' => false,
                'component' => 'Magento_Ui/js/form/components/group',
            ]
        );
        $meta = $this->arrayManager->set(
            $fromContainerPath . '/children/' . self::COURSE_END,
            $meta,
            $this->arrayManager->get($toFieldPath, $meta)
        );

        return $this->arrayManager->remove($toContainerPath, $meta);
    }
}
