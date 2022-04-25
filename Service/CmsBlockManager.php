<?php

declare(strict_types=1);
/**
 * @author    Trellis Team
 * @copyright Copyright © 2022 Trellis (https://www.trellis.co)
 */

namespace Trellis\Compliance\Service;

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Model\BlockFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;

class CmsBlockManager
{
    protected BlockFactory $blockFactory;

    protected BlockRepositoryInterface $blockRepository;

    protected SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @param BlockFactory             $blockFactory
     * @param BlockRepositoryInterface $blockRepository
     * @param SearchCriteriaBuilder    $searchCriteriaBuilder
     */
    public function __construct(
        BlockFactory $blockFactory,
        BlockRepositoryInterface $blockRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->blockFactory = $blockFactory;
        $this->blockRepository = $blockRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @throws LocalizedException
     */
    public function createBlock($data)
    {
        $newBlock = $this->blockFactory->create(['data' => $data]);
        $this->blockRepository->save($newBlock);
    }

    /**
     * @throws LocalizedException
     */
    public function deleteBlock($identifier)
    {
        $criteria = $this->searchCriteriaBuilder->addFilter('identifier', $identifier)->create();
        $list = $this->blockRepository->getList($criteria);
        foreach ($list->getItems() as $block) {
            $this->blockRepository->delete($block);
        }
    }
}
