<?php
declare(strict_types = 1);
namespace Favicode\Faq\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface QuestionsSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get news list.
     *
     * @return \Favicode\Faq\Api\Data\QuestionsInterface[]
     */
    public function getItems();

    /**
     * Set news list.
     *
     * @param \Favicode\Faq\Api\Data\QuestionsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
