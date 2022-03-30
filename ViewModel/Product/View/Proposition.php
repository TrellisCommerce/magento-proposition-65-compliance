<?php

declare(strict_types=1);
/**
 * @author    Trellis Team
 * @copyright Copyright © 2021 Trellis (https://www.trellis.co)
 */

namespace Trellis\Compliance\ViewModel\Product\View;

use Trellis\Compliance\Api\Data\ProductInterface;
use Exception;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\Cms\Block\BlockByIdentifier;
use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutInterface;

class Proposition extends DataObject implements ArgumentInterface
{
    protected Registry $coreRegistry;

    protected LayoutInterface $layout;

    protected ProductFactory $productFactory;

    protected Template $block;

    /**
     * @param Registry        $coreRegistry
     * @param LayoutInterface $layout
     * @param ProductFactory  $productFactory
     */
    public function __construct(
        Registry $coreRegistry,
        LayoutInterface $layout,
        ProductFactory $productFactory
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->layout = $layout;
        $this->productFactory = $productFactory;
    }

    /**
     * Retrieve currently viewed product object
     *
     * @return Product
     */
    public function getProduct(): Product
    {
        if (!$this->hasData('product')) {
            $this->setData('product', $this->coreRegistry->registry('product') ?? $this->productFactory->create());
        }

        return $this->getData('product');
    }

    /**
     * @return string
     */
    protected function getPropositionValue(): string
    {
        $attribute = $this->getProduct()->getCustomAttribute(ProductInterface::PROP65_ATTRIBUTE);
        $attributeValue = $attribute && $attribute->getValue() ? (string)$attribute->getValue() : "";
        $options = $this->getOptionValues();

        return $options[$attributeValue] ?? "";
    }

    /**
     * @param null $identifier
     *
     * @return string
     */
    public function getPropositionHtml($identifier = null): string
    {
        try {
            $html = $this->layout->createBlock(BlockByIdentifier::class)->setData(
                    'identifier',
                    $identifier ?? $this->getPropositionValue()
                )->toHtml();
        } catch (Exception $e) {
            $html = "";
        }

        return $html;
    }

    /**
     * @param Template $block
     */
    public function setBlock(Template $block)
    {
        $this->block = $block;
    }

    /**
     * @return Template
     */
    public function getBlock(): Template
    {
        return $this->block;
    }

    /**
     * @return array
     */
    public function getOptionValues(): array
    {
        return [
            ProductInterface::VALUE_WARNING_NO_WARNING => "",
            ProductInterface::VALUE_WARNING_1 => ProductInterface::CMS_BLOCK_IDENTIFIER_WARNING_1,
            ProductInterface::VALUE_WARNING_2 => ProductInterface::CMS_BLOCK_IDENTIFIER_WARNING_2,
        ];
    }
}
