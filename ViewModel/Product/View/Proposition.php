<?php

declare(strict_types=1);
/**
 * @author    Trellis Team
 * @copyright Copyright © 2022 Trellis (https://www.trellis.co)
 */

namespace Trellis\Compliance\ViewModel\Product\View;

use Magento\Catalog\Helper\Data;
use Trellis\Compliance\Api\Data\ProductInterface;
use Magento\Cms\Block\BlockByIdentifier;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutInterface;

class Proposition extends DataObject implements ArgumentInterface
{
    /** @var Template  */
    protected Template $block;

    protected Data $catalogHelper;

    protected LayoutInterface $layout;

    /**
     * @param Data            $catalogHelper
     * @param LayoutInterface $layout
     * @param array           $data
     */
    public function __construct(
        Data $catalogHelper,
        LayoutInterface $layout,
        array $data = []
    )
    {
        parent::__construct($data);
        $this->catalogHelper = $catalogHelper;
        $this->layout = $layout;
    }

    /**
     * @return string
     */
    protected function getPropositionValue(): string
    {
        $product = $this->catalogHelper->getProduct();
        if (!$product) {
            return '';
        }
        $attribute = $product->getCustomAttribute(ProductInterface::PROP65_ATTRIBUTE);
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
        } catch (\Exception $e) {
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
