<?php
declare(strict_types=1);

namespace Favicode\Faq\Controller\Adminhtml\Questions;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends \Magento\Backend\App\Action
{
    protected $questionsRepository;
    protected $customerRepository;


    public function __construct(
        \Magento\Backend\App\Action\Context               $context,
        \Favicode\Faq\Api\QuestionsRepositoryInterface    $questionRepository,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface
    )
    {
        $this->questionsRepository = $questionRepository;
        $this->customerRepository = $customerRepositoryInterface;
        return parent::__construct($context);
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function execute()
    {

        $postParameters = $this->getRequest()->getParams();
        $resultRedirect = $this->resultRedirectFactory->create();

        if (empty($postParameters['faq_id'])) {
            $this->messageManager->addErrorMessage("ID parameter was not given!");
            return $resultRedirect->setPath('*/*/index');
        }

        try {
            $question = $this->questionsRepository->getById((int)$postParameters['faq_id']);
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect->setPath('*/*/edit', ['faq_id' => $postParameters['faq_id']]);
        }

        $this->setQuestionParameters($question, $postParameters);

        try {
            $this->questionsRepository->save($question);
        } catch (CouldNotSaveException|LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect->setPath('*/*/edit', ['faq_id' => $question->getId()]);
        }

        $this->_eventManager->dispatch('question_answered', [
            'question' => $question->getQuestionText(),
            'answer' => $question->getQuestionAnswer(),
            'customer' => $this->customerRepository->getById($question->getCustomerId())
        ]);

        $this->messageManager->addSuccessMessage('Changes saved successfully!');
        return $resultRedirect->setPath('*/*/index');
    }

    /**
     * @param $question
     * @param array $postParameters
     * @return void
     */
    public function setQuestionParameters($question, array $postParameters): void
    {
        $question->setQuestionText($postParameters['question_text']);
        $question->setQuestionAnswer($postParameters['question_answer']);
        $question->setIsFaq((boolean)$postParameters['is_faq']);
        $question->setIsAnswered(true);
    }
}
