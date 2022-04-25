<?php

declare(strict_types=1);
/**
 * @author    Trellis Team
 * @copyright Copyright © 2022 Trellis (https://www.trellis.co)
 */

namespace Trellis\Compliance\Setup\Patch\Data;

use Trellis\Compliance\Api\Data\ProductInterface;
use Trellis\Compliance\Service\CmsBlockManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class AddCmsBlock implements DataPatchInterface, PatchRevertableInterface
{
    const STORE_ID = 0;

    protected ModuleDataSetupInterface $moduleDataSetup;

    protected CmsBlockManager $cmsBlockManager;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CmsBlockManager          $cmsBlockManager
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CmsBlockManager $cmsBlockManager
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->cmsBlockManager = $cmsBlockManager;
    }

    /**
     * @throws LocalizedException
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        $cmsBlocks = [
            [
                'title' => 'Warning #1',
                'identifier' => ProductInterface::CMS_BLOCK_IDENTIFIER_WARNING_1,
                'stores' => [self::STORE_ID],
                'is_active' => 1,
                'content' => '<span class="prop65-warning"><b>WARNING:</b> Products shown here contain substances known to the State of California to cause cancer or reproductive harm. For more information go to: <a href="https://www.p65warnings.ca.gov">https://www.p65warnings.ca.gov</a>.</span>',
            ],
            [
                'title' => 'Warning #2',
                'identifier' => ProductInterface::CMS_BLOCK_IDENTIFIER_WARNING_2,
                'stores' => [self::STORE_ID],
                'is_active' => 1,
                'content' => '<span class="prop65-warning"><b>WARNING:</b> Drilling, sawing, sanding, or machining wood products can expose you to wood dust and/or formaldehyde, substances known to the State of California to cause cancer or reproductive harm. Avoid inhaling wood dust or use a dust mask or other safeguards for personal protection. For more information go to: <a href="https://www.p65warnings.ca.gov/wood">https://www.p65warnings.ca.gov/wood</a>.</span>',
            ]
        ];

        foreach ($cmsBlocks as $cmsBlockData) {
            $this->cmsBlockManager->createBlock($cmsBlockData);
        }
        $this->moduleDataSetup->endSetup();
    }

    /**
     * @return array
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @throws LocalizedException
     */
    public function revert()
    {
        foreach (
            [
                ProductInterface::CMS_BLOCK_IDENTIFIER_WARNING_1,
                ProductInterface::CMS_BLOCK_IDENTIFIER_WARNING_2
            ] as $identifier
        ) {
            $this->cmsBlockManager->deleteBlock($identifier);
        }
    }
}
