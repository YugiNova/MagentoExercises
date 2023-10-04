<?php
namespace Magenest\Course\Setup\Patch\Data;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddCourseTime implements DataPatchInterface
{
    private $_moduleDataSetup;

    private $_eavSetupFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->_moduleDataSetup = $moduleDataSetup;
        $this->_eavSetupFactory = $eavSetupFactory;
    }

    public function apply()
    {
        $eavSetup = $this->_eavSetupFactory->create(['setup' => $this->_moduleDataSetup]);

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY, 'course_start_time', [
            'type' => 'datetime',
            'backend' => '',
            'frontend' => '',
            'label' => 'Course start time',
            'input' => 'date',
            'class' => '',
            'source' => '',
            'group' => 'Course Date',
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'sort_order' => '1',
            'unique' => false,
            'is_visible_in_grid' => true,
            'is_filterable_in_grid' => true,
            'apply_to' => 'simple,configurable,virtual,bundle,downloadable'
        ]);


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY, 'course_end_time', [
            'type' => 'datetime',
            'backend' => '',
            'frontend' => '',
            'label' => 'Course end time',
            'input' => 'date',
            'class' => '',
            'source' => '',
            'group' => 'Course Date',
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'sort_order' => '1',
            'unique' => false,
            'is_visible_in_grid' => true,
            'is_filterable_in_grid' => true,
            'apply_to' => 'simple,configurable,virtual,bundle,downloadable'
        ]);
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}
