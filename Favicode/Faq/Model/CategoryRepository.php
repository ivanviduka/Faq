<?php
declare(strict_types=1);

namespace Favicode\Faq\Model;

use Favicode\Faq\Api\CategoryRepositoryInterface;
use Favicode\Faq\Api\Data;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected $categoryModelFactory;

    protected $categoryResource;

    protected $categoryCollectionFactory;

    protected $searchResultsFactory;

    private $collectionProcessor;


    public function __construct(
        \Favicode\Faq\Api\Data\CategoryInterfaceFactory              $categoryModelFactory,
        \Favicode\Faq\Model\ResourceModel\Category                   $categoryResource,
        \Favicode\Faq\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Favicode\Faq\Api\Data\CategorySearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface                                 $collectionProcessor
    )
    {
        $this->categoryModelFactory = $categoryModelFactory;
        $this->categoryResource = $categoryResource;
        $this->categoryCollectionFactory = $categoryCollectionFactory;

        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }


    /**
     * @throws NoSuchEntityException
     */
    public function getById(int $categoryId)
    {
        $category = $this->categoryModelFactory->create();
        $this->categoryResource->load($category, $categoryId);

        if (!$category->getId()) {
            throw new NoSuchEntityException(__('Category with id "%1" does not exist.', $categoryId));
        }

        return $category;
    }

    /**
     * @throws CouldNotSaveException
     */
    public function save(Data\CategoryInterface $category)
    {
        try {
            $this->categoryResource->save($category);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $category;
    }

    /**
     * @throws CouldNotDeleteException
     */
    public function delete(Data\CategoryInterface $category)
    {
        try {
            $this->categoryResource->delete($category);

        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->categoryCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
