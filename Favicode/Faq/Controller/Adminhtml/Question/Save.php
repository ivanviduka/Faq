<?php
declare(strict_types=1);

namespace Favicode\Faq\Controller\Adminhtml\Question;

use Favicode\Faq\Api\QuestionRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var QuestionRepositoryInterface
     */
    protected $questionsRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Favicode_Faq::question');
    }

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        QuestionRepositoryInterface $questionRepository,
        LoggerInterface $logger
    ) {
        $this->questionsRepository = $questionRepository;
        $this->logger = $logger;
        parent::__construct($context);

    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $postRequest = $this->getRequest();
        $resultRedirect = $this->resultRedirectFactory->create();

        if (empty($postRequest->getParam('faq_id'))) {
            $this->messageManager->addErrorMessage(__('ID parameter was not given!'));
            return $resultRedirect->setPath('*/*/index');
        }

        try {
            $question = $this->questionsRepository->getById((int)$postRequest->getParam('faq_id'));
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
            $this->messageManager->addErrorMessage(__('Question could not be found!'));
            return $resultRedirect->setPath('*/*/edit', ['faq_id' => $postRequest->getParam('faq_id')]);
        }

        $this->setQuestionParameters($question, $postRequest);

        try {
            $this->questionsRepository->save($question);
        } catch (CouldNotSaveException|LocalizedException $e) {
            $this->logger->error($e->getMessage());
            $this->messageManager->addErrorMessage(__('Question could not be saved!'));
            return $resultRedirect->setPath('*/*/edit', ['faq_id' => $question->getId()]);
        }

        $this->messageManager->addSuccessMessage(__('Changes saved successfully!'));
        return $resultRedirect->setPath('*/*/index');
    }

    /**
     * @param $question
     * @param RequestInterface $request
     * @return void
     */
    public function setQuestionParameters($question, RequestInterface $request): void
    {
        $question->setQuestionText($request->getParam('question_text'));
        $question->setQuestionAnswer($request->getParam('question_answer'));
        $question->setIsFaq((boolean)$request->getParam('is_faq'));
        $question->setIsAnswered(true);
    }
}
