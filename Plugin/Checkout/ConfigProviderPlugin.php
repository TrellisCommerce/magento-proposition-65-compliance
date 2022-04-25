<?php

declare(strict_types=1);
/**
 * @author    Trellis Team
 * @copyright Copyright © 2022 Trellis (https://www.trellis.co)
 */

namespace Trellis\Compliance\Plugin\Checkout;

use Trellis\Compliance\ViewModel\Product\View\Proposition;
use Magento\Checkout\Model\DefaultConfigProvider;

class ConfigProviderPlugin
{
    protected Proposition $propositionBlock;

    /**
     * @param Proposition $propositionBlock
     */
    public function __construct(Proposition $propositionBlock)
    {
        $this->propositionBlock = $propositionBlock;
    }

    /**
     * @param DefaultConfigProvider $subject
     * @param array                 $result
     *
     * @return array
     */
    public function afterGetConfig(DefaultConfigProvider $subject, array $result): array
    {
        if (is_array($result['quoteItemData'])) {
            foreach ($result['quoteItemData'] as &$itemData) {
                $itemData['user_compliance'] = $this->getHtml($itemData['product']);
            }
        }

        return $result;
    }

    /**
     * @param array $product
     *
     * @return string
     */
    protected function getHtml(array $product): string
    {
        $options = $this->propositionBlock->getOptionValues();

        return (key_exists('user_compliance_prop65', $product) && $product['user_compliance_prop65'])
            ? $this->propositionBlock->getPropositionHtml($options[$product['user_compliance_prop65']] ?? "") : "";
    }
}
