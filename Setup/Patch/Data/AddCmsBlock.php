<?php
declare(strict_types=1);
/**
 * @author    Trellis Team
 * @copyright Copyright © 2021 Trellis (https://www.trellis.co)
 */

namespace Trellis\Compliance\Setup\Patch\Data;

use Trellis\Compliance\Api\Data\ProductInterface;
use Trellis\Compliance\Service\CmsBlockManager;
use Trellis\Compliance\Service\ImageManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class AddCmsBlock implements DataPatchInterface, PatchRevertableInterface
{
    const STORE_ID = 0;

    private ModuleDataSetupInterface $moduleDataSetup;
    private CmsBlockManager $cmsBlockManager;
    private ImageManager $imageManager;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CmsBlockManager $cmsBlockManager,
        ImageManager $imageManager
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->cmsBlockManager = $cmsBlockManager;
        $this->imageManager = $imageManager;
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
                'content' => $this->getContent(ProductInterface::CMS_BLOCK_IDENTIFIER_WARNING_1),
            ],
            ['title' => 'Warning #2',
                'identifier' => ProductInterface::CMS_BLOCK_IDENTIFIER_WARNING_2,
                'stores' => [self::STORE_ID],
                'is_active' => 1,
                'content' => $this->getContent(ProductInterface::CMS_BLOCK_IDENTIFIER_WARNING_2),
            ]
        ];
        foreach ($cmsBlocks as $cmsBlockData) {
            $this->cmsBlockManager->createBlock($cmsBlockData);
        }
        $this->moduleDataSetup->endSetup();
    }

    private function getContent($blockIdentifier): string
    {
        return $this->getCssStyle() .
            $this->imageManager->getImageTag() .
            $this->getText()[$blockIdentifier];
    }

    private function getText(): array
    {
        return [
            ProductInterface::CMS_BLOCK_IDENTIFIER_WARNING_1 => '<b>WARNING:</b> Products shown here contain substances known to the State of California to cause cancer or reproductive harm. For more information go to: <a href="https://www.p65warnings.ca.gov">https://www.p65warnings.ca.gov</a>',
            ProductInterface::CMS_BLOCK_IDENTIFIER_WARNING_2 => '<b>WARNING:</b> Drilling, sawing, sanding, or machining wood products can expose you to wood dust and/or formaldehyde, substances known to the State of California to cause cancer or reproductive harm. Avoid inhaling wood dust or use a dust mask or other safeguards for personal protection. For more information go to: <a href="https://www.p65warnings.ca.gov/wood">https://www.p65warnings.ca.gov/wood</a>'
        ];
    }

    private function getCssStyle()
    {
        return "<style>.warning-message{max-width:6%;height:auto;float:left;margin:5px 10px;}</style>";
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    /**
     * @throws LocalizedException
     */
    public function revert()
    {
        foreach ([ProductInterface::CMS_BLOCK_IDENTIFIER_WARNING_1, ProductInterface::CMS_BLOCK_IDENTIFIER_WARNING_2] as $identifier) {
            $this->cmsBlockManager->deleteBlock($identifier);
        }
    }
}
