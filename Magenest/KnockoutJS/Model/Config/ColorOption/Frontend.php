<?php

namespace Magenest\KnockoutJS\Model\Config\ColorOption;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class Frontend extends AbstractFieldArray
{
    private $colorPicker;

    protected function getColorPickerRenderer()
    {
        if (!$this->colorPicker) {
            $this->colorPicker = $this->getLayout()->createBlock(
                \Magenest\KnockoutJS\Block\Config\ColorPicker::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->colorPicker;
    }

    /**
     * Prepare to render
     *
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'color_label',
            [
                'label' => __('Label'),
                'class' => 'required-entry'
            ]
        );
        $this->addColumn(
            'color_value',
            [
                'label' => __('Color'),
                'renderer' => $this->getColorPickerRenderer()
            ]
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Color Option');
    }

    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $colorPicker = $this->colorPicker;
        if (isset($row['color_value'])) {
            $color = $row->getColorValue(); // getting hex code from dv
            $row->setData('backgroundcolor', $color);
            // how to add style here like style="background-color:#FF0000;"
        }
        $debug = $row;
    }
}
