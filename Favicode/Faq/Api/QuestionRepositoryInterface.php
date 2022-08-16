<?php
declare(strict_types=1);

namespace Favicode\Faq\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface QuestionRepositoryInterface
{
    /**
     * @param int $questionId
     * @return mixed
     */
    public function getById(int $questionId);

    /**
     * @param Data\QuestionInterface $question
     * @return mixed
     */
    public function save(Data\QuestionInterface $question);

    /**
     * @param Data\QuestionInterface $question
     * @return mixed
     */
    public function delete(Data\QuestionInterface $question);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

}
