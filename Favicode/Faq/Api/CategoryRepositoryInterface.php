<?php
declare(strict_types=1);

namespace Favicode\Faq\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface CategoryRepositoryInterface
{
    /**
     * @param int $categoryId
     * @return mixed
     */
    public function getById(int $categoryId);

    /**
     * @param Data\CategoryInterface $category
     * @return mixed
     */
    public function save(Data\CategoryInterface $category);

    /**
     * @param Data\CategoryInterface $category
     * @return mixed
     */
    public function delete(Data\CategoryInterface $category);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
