<?php
declare(strict_types=1);

namespace Favicode\Faq\Controller\Adminhtml\Question;

use Favicode\Faq\Api\QuestionRepositoryInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class ChangeStatus extends \Magento\Backend\App\Action
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
     * @return Redirect
     */
    public function execute()
    {
        $questionID = $this->getRequest()->getParam('faq_id');
        $resultRedirect = $this->resultRedirectFactory->create();

        if (empty($questionID)) {
            $this->messageManager->addErrorMessage('ID parameter was not given!');
            return $resultRedirect->setPath('*/*/index');
        }

        try {
            $question = $this->questionsRepository->getById((int)$questionID);
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
            $this->messageManager->addErrorMessage(__('Question was not found!'));
            return $resultRedirect->setPath('*/*/index');
        }

        if (!$question->getIsAnswered() || empty($question->getQuestionAnswer())) {
            $this->messageManager->addErrorMessage(__('Question without answer cannot be listed in FAQ!'));
            return $resultRedirect->setPath('*/*/index');
        }

        $question->setIsFaq(!$question->getIsFaq());

        try {
            $this->questionsRepository->save($question);
        } catch (CouldNotSaveException|LocalizedException $e) {
            $this->logger->error($e->getMessage());
            $this->messageManager->addErrorMessage(__('Question status could not be changed!'));
            return $resultRedirect->setPath('*/*/index');
        }

        $this->messageManager->addSuccessMessage(__('Question FAQ status changed!'));
        return $resultRedirect->setPath('*/*/index');
    }
}

