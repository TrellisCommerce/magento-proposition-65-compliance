<?php

declare(strict_types=1);
/**
 * @author    Trellis Team
 * @copyright Copyright © 2021 Trellis (https://www.trellis.co)
 */

namespace Trellis\Compliance\Setup\Patch\Data;

use Trellis\Compliance\Api\Data\ProductInterface;
use Trellis\Compliance\Model\Source\Product\Prop65Source;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class UpdateProp65Attribute implements DataPatchInterface
{
    const PROP65_LABEL = 'Proposition 65';

    protected EavSetupFactory $eavSetupFactory;

    protected ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @param EavSetupFactory          $eavSetupFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @return void
     */
    public function apply()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->updateAttribute(
            Product::ENTITY,
            ProductInterface::PROP65_ATTRIBUTE,
            'backend_model'
        );
    }

    /**
     * @return string[]
     */
    public static function getDependencies(): array
    {
        return [
            AddProp65Attribute::class
        ];
    }

    /**
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }
}
