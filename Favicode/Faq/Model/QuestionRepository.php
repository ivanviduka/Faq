<?php
declare(strict_types=1);
namespace Favicode\Faq\Model;

use Favicode\Faq\Api\Data;
use Favicode\Faq\Api\QuestionRepositoryInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class QuestionRepository implements QuestionRepositoryInterface
{

    /**
     * @var Data\QuestionInterfaceFactory
     */
    protected $questionModelFactory;

    /**
     * @var ResourceModel\Question
     */
    protected $questionResource;

    /**
     * @var ResourceModel\Question\CollectionFactory
     */
    protected $questionCollectionFactory;

    /**
     * @var Data\QuestionSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    public function __construct(
        \Favicode\Faq\Api\Data\QuestionInterfaceFactory $questionModelFactory,
        \Favicode\Faq\Model\ResourceModel\Question $questionResource,
        \Favicode\Faq\Model\ResourceModel\Question\CollectionFactory $questionCollectionFactory,
        \Favicode\Faq\Api\Data\QuestionSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->questionModelFactory = $questionModelFactory;
        $this->questionResource = $questionResource;
        $this->questionCollectionFactory = $questionCollectionFactory;

        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @param int $questionId
     * @return Data\QuestionInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $questionId): Data\QuestionInterface
    {
        $question = $this->questionModelFactory->create();
        $this->questionResource->load($question, $questionId);

        if (!$question->getId()) {
            throw new NoSuchEntityException(__('Question with id "%1" does not exist.', $questionId));
        }

        return $question;
    }

    /**
     * @param Data\QuestionInterface $question
     * @return Data\QuestionInterface
     * @throws CouldNotSaveException
     */
    public function save(Data\QuestionInterface $question)
    {
        try {
            $this->questionResource->save($question);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $question;
    }

    /**
     * @param Data\QuestionInterface $question
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Data\QuestionInterface $question)
    {
        try {
            $this->questionResource->delete($question);

        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return Data\QuestionSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->questionCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
