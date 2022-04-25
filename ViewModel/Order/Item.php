<?php

declare(strict_types=1);
/**
 * @author    Trellis Team
 * @copyright Copyright © 2022 Trellis (https://www.trellis.co)
 */

namespace Trellis\Compliance\ViewModel\Order;

use Magento\Catalog\Helper\Data;
use Magento\Framework\View\LayoutInterface;
use Magento\Catalog\Model\ProductFactory;
use Trellis\Compliance\ViewModel\Product\View\Proposition;
use Magento\Catalog\Model\Product;

class Item extends Proposition
{
    protected ProductFactory $productFactory;

    /**
     * @param Data            $catalogHelper
     * @param LayoutInterface $layout
     * @param ProductFactory  $productFactory
     * @param array           $data
     */
    public function __construct(
        Data $catalogHelper,
        LayoutInterface $layout,
        ProductFactory $productFactory,
        array $data = []
    ) {
        parent::__construct($catalogHelper, $layout, $data);
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
            $product = $this->productFactory->create();
            $block = $this->getBlock();
            if ($block && $block->getParentBlock() && $block->getParentBlock()->getItem()) {
                $product = $block->getParentBlock()->getItem()->getProduct() ?? $product;
            }
            $this->setData('product', $product);
        }

        return $this->getData('product');
    }
}
