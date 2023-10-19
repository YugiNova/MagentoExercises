<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\Blog\Setup\Patch\Data;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Config;

class AddVnRegionAttribute implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    private $eavSetupFactory;
    private $customerSetupFactory;
    private $eavConfig;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory          $eavSetupFactory,
        CustomerSetupFactory     $customerSetupFactory,
        Config $eavConfig
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->customerSetupFactory = $customerSetupFactory;
        $this->eavConfig = $eavConfig;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->removeAttribute('customer_address','vn_region');
        $eavSetup->removeAttribute('customer_address','custom_address_attribute');
        $eavSetup->addAttribute('customer_address', 'vn_region', [
            'type' => 'int',
            'input' => 'select',
            'label' => 'VN Region',
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'system'=> false,
            'global' => true,
            'visible_on_front' => true,
            'backend' => 'Magenest\Blog\Model\Customer\Attribute\BackEnd\VnRegion',
            'source' => 'Magenest\Blog\Model\Customer\Attribute\Source\VnRegion',
        ]);

        $customAttribute = $this->eavConfig->getAttribute('customer_address', 'vn_region');
        $customAttribute->setData(
            'used_in_forms',
            [
                'adminhtml_customer',
                'adminhtml_checkout',
                'customer_account_create',
                'customer_account_edit',
                'adminhtml_customer_address',
                'customer_address_edit'
            ],
        );
        $customAttribute->save();
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [
        ];
    }

    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        /**
         * This internal method, that means that some patches with time can change their names,
         * but changing name should not affect installation process, that's why if we will change name of the patch
         * we will add alias here
         */
        return [];
    }
}
