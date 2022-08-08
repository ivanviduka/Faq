<?php

namespace Favicode\Faq\Controller\Question;

use Magento\Framework\App\Action\HttpGetActionInterface;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;

class Delete implements ActionInterface, HttpGetActionInterface
{

    protected $questionsRepository;
    protected $customerSession;
    protected $request;
    protected $redirect;
    protected $redirectFactory;
    protected $messageManager;

    public function __construct(
        \Magento\Framework\App\RequestInterface          $request,
        RedirectInterface                                $redirect,
        ManagerInterface                                 $messageManager,
        RedirectFactory                                  $redirectFactory,
        \Favicode\Faq\Api\QuestionsRepositoryInterface   $questionsRepository,
        \Magento\Customer\Model\Session                  $customerSession)
    {

        $this->request = $request;
        $this->redirect = $redirect;
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->questionsRepository = $questionsRepository;
        $this->customerSession = $customerSession;


    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {

        $resultRedirect = $this->redirectFactory->create();

        if (!$this->isLoggedIn()) {
            $this->messageManager->addErrorMessage("You must be logged in to access this page!");
            return $resultRedirect->setUrl($this->redirect->getRefererUrl());
        }

        $questionId = $this->request->getParam('question_id');

        if (empty($questionId)) {
            $this->messageManager->addErrorMessage("Question ID is required!");
            return $resultRedirect->setUrl($this->redirect->getRefererUrl());
        }

        try {

            $question = $this->questionsRepository->getById((int)$questionId);
            if (!$this->isOwner($question->getCustomerId())) {
                $this->messageManager->addErrorMessage("Forbidden access!");
                return $resultRedirect->setUrl($this->redirect->getRefererUrl());
            }

            $this->questionsRepository->delete($question);

        } catch (NoSuchEntityException|CouldNotDeleteException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $this->messageManager->addSuccessMessage('Question has been deleted successfully!');
        return $resultRedirect->setUrl($this->redirect->getRefererUrl());
    }

    private function isLoggedIn(): bool
    {
        return $this->customerSession->isLoggedIn();
    }

    private function isOwner(string $getCustomerId): bool
    {
        return $this->customerSession->getId() === $getCustomerId;
    }
}
