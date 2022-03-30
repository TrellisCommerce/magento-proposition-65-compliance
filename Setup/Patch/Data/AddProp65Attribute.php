<?php

declare(strict_types=1);
/**
 * @author    Trellis Team
 * @copyright Copyright © 2021 Trellis (https://www.trellis.co)
 */

namespace Trellis\Compliance\Setup\Patch\Data;

use Trellis\Compliance\Api\Data\ProductInterface;
use Trellis\Compliance\Model\Source\Product\Prop65Source;
use Exception;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Zend_Validate_Exception;

class AddProp65Attribute implements DataPatchInterface, PatchRevertableInterface
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
     * @throws LocalizedException
     * @throws Zend_Validate_Exception
     */
    public function apply()
    {
        $this->revert(); // removes the attribute if it already exists
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->addAttribute(Product::ENTITY, ProductInterface::PROP65_ATTRIBUTE, [
            'type' => 'varchar',
            'frontend' => '',
            'label' => self::PROP65_LABEL,
            'input' => 'select',
            'source' => Prop65Source::class,
            'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'default' => 0,
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => true,
            'used_in_product_listing' => true,
            'unique' => false,
            'apply_to' => ''
        ]);
        $this->addToAttributeSet($eavSetup);
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

    public function revert()
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        if ($eavSetup->getAttribute(Product::ENTITY, ProductInterface::PROP65_ATTRIBUTE)) {
            $eavSetup->removeAttribute(
                Product::ENTITY,
                ProductInterface::PROP65_ATTRIBUTE
            );
        }
    }

    /**
     * @param EavSetup $eavSetup
     */
    protected function addToAttributeSet(EavSetup $eavSetup): void
    {
        $attributeId = $eavSetup->getAttributeId(
            Product::ENTITY,
            ProductInterface::PROP65_ATTRIBUTE
        );
        $attributeSetIds = $eavSetup->getAllAttributeSetIds(
            Product::ENTITY
        );
        foreach ($attributeSetIds as $attributeSetId) {
            try {
                $attributeGroupId = $eavSetup->getAttributeGroupId(
                    Product::ENTITY,
                    $attributeSetId,
                    'Product Details',
                );
            } catch (Exception $e) {
                $attributeGroupId = $eavSetup->getDefaultAttributeGroupId(
                    Product::ENTITY,
                    $attributeSetId
                );
            }
            $eavSetup->addAttributeToSet(
                Product::ENTITY,
                $attributeSetId,
                $attributeGroupId,
                $attributeId
            );
        }
    }
}
