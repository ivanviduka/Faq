<?php

namespace Favicode\Faq\Model;

use Favicode\Faq\Api\Data;
use Favicode\Faq\Api\QuestionsRepositoryInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class QuestionsRepository implements QuestionsRepositoryInterface
{
    protected $questionsModelFactory;

    protected $questionsResource;

    protected $questionsCollectionFactory;

    protected $searchResultsFactory;

    private $collectionProcessor;


    public function __construct(
        \Favicode\Faq\Api\Data\QuestionsInterfaceFactory              $questionsModelFactory,
        \Favicode\Faq\Model\ResourceModel\Questions                   $questionsResource,
        \Favicode\Faq\Model\ResourceModel\Questions\CollectionFactory $questionsCollectionFactory,
        \Favicode\Faq\Api\Data\QuestionsSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface                                  $collectionProcessor
    )
    {
        $this->questionsModelFactory = $questionsModelFactory;
        $this->questionsResource = $questionsResource;
        $this->questionsCollectionFactory = $questionsCollectionFactory;

        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    public function getById(int $questionId): Data\QuestionsInterface
    {
        $question = $this->questionsModelFactory->create();
        $this->questionsResource->load($question, $questionId);

        if (!$question->getId()) {
            throw new NoSuchEntityException(__('Question with id "%1" does not exist.', $questionId));
        }

        return $question;
    }

    /**
     * @throws CouldNotSaveException
     */
    public function save(Data\QuestionsInterface $question)
    {
        try {
            $this->questionsResource->save($question);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $question;
    }

    /**
     * @throws CouldNotDeleteException
     */
    public function delete(Data\QuestionsInterface $question)
    {
        try {
            $this->questionsResource->delete($question);

        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->questionsCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
