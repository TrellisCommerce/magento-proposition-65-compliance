<?php

declare(strict_types=1);
/**
 * @author    Trellis Team
 * @copyright Copyright © 2022 Trellis (https://www.trellis.co)
 */

namespace Trellis\Compliance\Model\Source\Product;

use Trellis\Compliance\Api\Data\ProductInterface;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Prop65Source extends AbstractSource
{
    /**
     * Return the compliance CMS block list
     *
     * @return array[]
     */
    public function getAllOptions(): array
    {
        return [
            ['value' => ProductInterface::VALUE_WARNING_NO_WARNING, 'label' => __('No Warning Message')],
            ['value' => ProductInterface::VALUE_WARNING_1, 'label' => __('Generic Warning Message')],
            ['value' => ProductInterface::VALUE_WARNING_2, 'label' => __('Detailed Warning Message')],
        ];
    }
}
