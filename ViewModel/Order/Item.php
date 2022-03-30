<?php

declare(strict_types=1);
/**
 * @author    Trellis Team
 * @copyright Copyright © 2021 Trellis (https://www.trellis.co)
 */

namespace Trellis\Compliance\ViewModel\Order;

use Trellis\Compliance\ViewModel\Product\View\Proposition;
use Magento\Catalog\Model\Product;

class Item extends Proposition
{
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
