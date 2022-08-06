<?php
declare(strict_types=1);
namespace Favicode\Faq\Controller\Question;


use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;

class Save implements ActionInterface, HttpPostActionInterface
{
    protected $questionsRepository;
    protected $questions;
    protected $customerSession;
    protected $storeManager;
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
        \Favicode\Faq\Api\Data\QuestionsInterfaceFactory $questions,
        \Magento\Customer\Model\Session                  $customerSession,
        \Magento\Store\Model\StoreManagerInterface       $storeManager)
    {

        $this->request = $request;
        $this->redirect = $redirect;
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->questionsRepository = $questionsRepository;
        $this->questions = $questions;
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;

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

        $postParameters = $this->request->getParams();

        if (empty($postParameters['question_text'])) {
            $this->messageManager->addErrorMessage("Question text is required!");
            return $resultRedirect->setUrl($this->redirect->getRefererUrl());
        }

        $question = $this->questions->create();
        $this->setQuestionParameters($question, $postParameters['question_text']);

        try {
            $this->questionsRepository->save($question);
        } catch (CouldNotSaveException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (LocalizedException $e) {
            error_log($e->getMessage());
        }

        $this->messageManager->addSuccessMessage('Your question has been saved successfully!');
        return $resultRedirect->setUrl($this->redirect->getRefererUrl());
    }

    private function isLoggedIn(): bool
    {
        return $this->customerSession->isLoggedIn();
    }

    /**
     * @param \Favicode\Faq\Api\Data\QuestionsInterface $question
     * @param $question_text
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function setQuestionParameters(\Favicode\Faq\Api\Data\QuestionsInterface $question, $question_text): void
    {
        $question->setQuestionText($question_text);
        $question->setCustomerId((string)$this->customerSession->getId());
        $question->setStoreId((string)$this->storeManager->getStore()->getId());
        $question->setProductId($this->request->getParam('product_id'));
    }
}

