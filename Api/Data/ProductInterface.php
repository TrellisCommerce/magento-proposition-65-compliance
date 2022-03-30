<?php

declare(strict_types=1);
/**
 * @author    Trellis Team
 * @copyright Copyright © 2021 Trellis (https://www.trellis.co)
 */

namespace Trellis\Compliance\Api\Data;

interface ProductInterface extends \Magento\Catalog\Api\Data\ProductInterface
{
    const PROP65_ATTRIBUTE = 'user_compliance_prop65';
    const VALUE_WARNING_NO_WARNING = 0;
    const VALUE_WARNING_1 = 1;
    const VALUE_WARNING_2 = 2;
    const CMS_BLOCK_IDENTIFIER_WARNING_1 = 'prop65_warning1';
    const CMS_BLOCK_IDENTIFIER_WARNING_2 = 'prop65_warning2';
}
