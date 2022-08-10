<?php
declare(strict_types=1);

namespace Favicode\Faq\Controller\Question;

use Favicode\Faq\Api\CategoryRepositoryInterface;
use Favicode\Faq\Api\Data\QuestionsInterface;
use Favicode\Faq\Api\Data\QuestionsInterfaceFactory;
use Favicode\Faq\Api\QuestionsRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;

class Save implements ActionInterface, HttpPostActionInterface
{
    protected $questionsRepository;
    protected $categoryRepository;
    protected $questions;
    protected $customerSession;
    protected $storeManager;
    protected $request;
    protected $redirect;
    protected $redirectFactory;
    protected $messageManager;
    protected $eventManager;

    public function __construct(
        RequestInterface                          $request,
        RedirectInterface                         $redirect,
        ManagerInterface                          $messageManager,
        RedirectFactory                           $redirectFactory,
        QuestionsRepositoryInterface              $questionsRepository,
        CategoryRepositoryInterface               $categoryRepository,
        QuestionsInterfaceFactory                 $questions,
        Session                                   $customerSession,
        StoreManagerInterface                     $storeManager,
        \Magento\Framework\Event\ManagerInterface $eventManager)
    {

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

    }

    /**
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $resultRedirect = $this->redirectFactory->create();

        if (!$this->isLoggedIn()) {
            $this->messageManager->addErrorMessage("You must be logged in to access this page!");
            return $resultRedirect->setUrl($this->redirect->getRefererUrl());
        }

        $postParameters = $this->request->getParams();

        if (empty($postParameters['question_text'])) {
            $this->messageManager->addErrorMessage("Question text is required!");
            return $resultRedirect->setUrl($this->redirect->getRefererUrl());
        }

        try {
            $category = $this->categoryRepository->getById((int)$postParameters['category_id']);
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect->setUrl($this->redirect->getRefererUrl());
        }

        $question = $this->questions->create();
        $this->setQuestionParameters($question, $postParameters);

        try {
            $this->questionsRepository->save($question);
        } catch (CouldNotSaveException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect->setUrl($this->redirect->getRefererUrl());
        } catch (LocalizedException $e) {
            error_log($e->getMessage());
        }

        $this->eventManager->dispatch('question_submitted', [
            'customer' => $this->customerSession->getCustomer(),
            'product_id' => $this->request->getParam('product_id'),
            'store' => $this->storeManager->getStore()]);

        $this->messageManager->addSuccessMessage('Your question has been saved successfully!');
        return $resultRedirect->setUrl($this->redirect->getRefererUrl());
    }

    private function isLoggedIn(): bool
    {
        return $this->customerSession->isLoggedIn();
    }

    /**
     * @param QuestionsInterface $question
     * @param array $parameters
     * @return void
     * @throws NoSuchEntityException
     */
    public function setQuestionParameters(QuestionsInterface $question, array $parameters): void
    {
        $question->setQuestionText($parameters['question_text']);
        $question->setCustomerId((string)$this->customerSession->getId());
        $question->setStoreId((string)$this->storeManager->getStore()->getId());
        $question->setProductId($parameters['product_id']);
        $question->setCategoryId($parameters['category_id']);
    }
}

