<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\Movie\Setup\Patch\Data;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magenest\Movie\Model\ActorFactory;
use Magento\Framework\HTTP\Client\Curl;

class InitActor implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    private $actorFactory;
    private $curl;
    private const ACTORS_API_URL = 'https://6401dc9d0a2a1afebef3c167.mockapi.io/actors';

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        ActorFactory $actorFactory,
        Curl $curl,
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->actorFactory = $actorFactory;
        $this->curl = $curl;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $this->curl->get(self::ACTORS_API_URL);
        $actors = json_decode($this->curl->getBody());
        foreach($actors as $actor)
        {
            $this->actorFactory->create()->addData([
                'name' => $actor->name
            ])->save();
        }

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
