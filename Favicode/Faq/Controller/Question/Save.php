<?php
declare(strict_types=1);

namespace Favicode\Faq\Controller\Question;

use Favicode\Faq\Api\CategoryRepositoryInterface;
use Favicode\Faq\Api\Data\QuestionInterface;
use Favicode\Faq\Api\Data\QuestionInterfaceFactory;
use Favicode\Faq\Api\QuestionRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Save implements ActionInterface, HttpPostActionInterface
{
    /**
     * @var QuestionRepositoryInterface
     */
    protected $questionsRepository;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var QuestionInterfaceFactory
     */
    protected $questions;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var RequestInterface
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
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        RequestInterface $request,
        RedirectInterface $redirect,
        ManagerInterface $messageManager,
        RedirectFactory $redirectFactory,
        QuestionRepositoryInterface $questionsRepository,
        CategoryRepositoryInterface $categoryRepository,
        QuestionInterfaceFactory $questions,
        Session $customerSession,
        StoreManagerInterface $storeManager,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        LoggerInterface $logger
    ) {
        $this->request = $request;
        $this->redirect = $redirect;
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->questionsRepository = $questionsRepository;
        $this->categoryRepository = $categoryRepository;
        $this->questions = $questions;
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;
        $this->eventManager = $eventManager;

        $this->logger = $logger;
    }

    /**
     * @return Redirect
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $resultRedirect = $this->redirectFactory->create();

        if (!$this->isLoggedIn()) {
            $this->messageManager->addErrorMessage(__('You must be logged in to access this page!'));
            return $resultRedirect->setUrl($this->redirect->getRefererUrl());
        }

        $postRequest = $this->request;

        if (empty($postRequest->getParam('question_text'))) {
            $this->messageManager->addErrorMessage(__('Question text is required!'));
            return $resultRedirect->setUrl($this->redirect->getRefererUrl());
        }

        try {
            $category = $this->categoryRepository->getById((int)$postRequest->getParam('category_id'));
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
            $this->messageManager->addErrorMessage(__('Category does not exist!'));
            return $resultRedirect->setUrl($this->redirect->getRefererUrl());
        }

        $question = $this->questions->create();
        $this->setQuestionParameters($question, $postRequest);

        try {
            $this->questionsRepository->save($question);
        } catch (CouldNotSaveException $e) {
            $this->logger->error($e->getMessage());
            $this->messageManager->addErrorMessage(__('Question could not be saved!'));
            return $resultRedirect->setUrl($this->redirect->getRefererUrl());
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
            return $resultRedirect->setUrl($this->redirect->getRefererUrl());
        }

        $this->messageManager->addSuccessMessage(__('Your question has been saved successfully!'));
        return $resultRedirect->setUrl($this->redirect->getRefererUrl());
    }

    /**
     * @return bool
     */
    private function isLoggedIn(): bool
    {
        return $this->customerSession->isLoggedIn();
    }

    /**
     * @param QuestionInterface $question
     * @param RequestInterface $request
     * @return void
     * @throws NoSuchEntityException
     */
    public function setQuestionParameters(QuestionInterface $question, RequestInterface $request): void
    {
        $question->setQuestionText($request->getParam('question_text'));
        $question->setCustomerId((string)$this->customerSession->getId());
        $question->setStoreId((string)$this->storeManager->getStore()->getId());
        $question->setProductId($request->getParam('product_id'));
        $question->setCategoryId($request->getParam('category_id'));
    }
}

