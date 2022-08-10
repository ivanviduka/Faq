<?php
declare(strict_types=1);

namespace Favicode\Faq\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface CategoryRepositoryInterface
{
    public function getById(int $categoryId);

    public function save(Data\CategoryInterface $category);

    public function delete(Data\CategoryInterface $category);

    public function getList(SearchCriteriaInterface $searchCriteria);
}
