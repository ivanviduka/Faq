<?php
declare(strict_types = 1);
namespace Favicode\Faq\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface QuestionsRepositoryInterface
{

    public function getById(int $questionId);

    public function save(Data\QuestionsInterface $question);

    public function delete(Data\QuestionsInterface $question);

    public function getList(SearchCriteriaInterface $searchCriteria);

}
