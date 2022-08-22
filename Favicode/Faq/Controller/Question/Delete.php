<?php
declare(strict_types=1);

namespace Favicode\Faq\Controller\Question;

use Magento\Customer\Controller\AccountInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Psr\Log\LoggerInterface;

class Delete implements AccountInterface, HttpGetActionInterface
{

    /**
     * @var \Favicode\Faq\Api\QuestionRepositoryInterface
     */
    protected $questionsRepository;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var RedirectInterface
     */
    protected $redirect;

    /**
     * @var RedirectFactory
     */
    protected $redirectFactory;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        RedirectInterface $redirect,
        ManagerInterface $messageManager,
        RedirectFactory $redirectFactory,
        \Favicode\Faq\Api\QuestionRepositoryInterface $questionsRepository,
        \Magento\Customer\Model\Session $customerSession,
        LoggerInterface $logger
    ) {
        $this->request = $request;
        $this->redirect = $redirect;
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->questionsRepository = $questionsRepository;
        $this->customerSession = $customerSession;
        $this->logger = $logger;
    }

    /**
     * @return Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->redirectFactory->create();


        $questionId = $this->request->getParam('question_id');

        if (empty($questionId)) {
            $this->messageManager->addErrorMessage(__('Question ID is required!'));
            return $resultRedirect->setUrl($this->redirect->getRefererUrl());
        }

        try {

            $question = $this->questionsRepository->getById((int)$questionId);
            if (!$this->isOwner($question->getCustomerId())) {
                $this->messageManager->addErrorMessage('Forbidden access!');
                return $resultRedirect->setUrl($this->redirect->getRefererUrl());
            }

            $this->questionsRepository->delete($question);

        } catch (NoSuchEntityException|CouldNotDeleteException $e) {
            $this->logger->error($e->getMessage());
            $this->messageManager->addErrorMessage(__('Question could not be deleted'));
            return $resultRedirect->setUrl($this->redirect->getRefererUrl());
        }

        $this->messageManager->addSuccessMessage(__('Question has been deleted successfully!'));
        return $resultRedirect->setUrl($this->redirect->getRefererUrl());

    }

    /**
     * @param string $getCustomerId
     * @return bool
     */
    private function isOwner(string $getCustomerId): bool
    {
        return $this->customerSession->getId() === $getCustomerId;
    }
}
