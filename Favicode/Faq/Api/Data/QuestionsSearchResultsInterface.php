<?php
declare(strict_types=1);

namespace Favicode\Faq\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface QuestionsSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get questions list.
     *
     * @return \Favicode\Faq\Api\Data\QuestionsInterface[]
     */
    public function getItems();

    /**
     * Set questions list.
     *
     * @param \Favicode\Faq\Api\Data\QuestionsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
