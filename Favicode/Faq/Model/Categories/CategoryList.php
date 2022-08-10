<?php
declare(strict_types=1);

namespace Favicode\Faq\Model\Categories;

use Favicode\Faq\Api\CategoryRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class CategoryList implements \Magento\Framework\Option\ArrayInterface
{
    protected $searchCriteriaBuilder;
    protected $categoryRepository;

    public function __construct(
        SearchCriteriaBuilder       $searchCriteriaBuilder,
        CategoryRepositoryInterface $categoryRepository
    )
    {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->categoryRepository = $categoryRepository;
    }

    public function toOptionArray(): array
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $categories = $this->categoryRepository->getList($searchCriteria)->getItems();

        $options = [];

        foreach ($categories as $category) {
            $options[] = ['label' => $category->getCategoryName(), 'value' => $category->getCategoryId()];
        }

        return $options;
    }
}
