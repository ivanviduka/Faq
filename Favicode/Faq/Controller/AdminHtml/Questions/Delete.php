<?php
declare(strict_types=1);
namespace Favicode\Faq\Controller\AdminHtml\Questions;


use Magento\Framework\Exception\LocalizedException;

class Delete extends \Magento\Backend\App\Action
{
    protected $questionsRepository;

    public function __construct(
        \Magento\Backend\App\Action\Context            $context,
        \Favicode\Faq\Api\QuestionsRepositoryInterface $questionRepository
    )
    {
        $this->questionsRepository = $questionRepository;
        return parent::__construct($context);
    }


    public function execute()
    {
        $questionID = $this->getRequest()->getParam('faq_id');
        $resultRedirect = $this->resultRedirectFactory->create();

        if (empty($questionID)) {
            $this->messageManager->addErrorMessage("ID parameter was not given!");
            return $resultRedirect->setPath('*/*/index');
        }

        try {
            $question = $this->questionsRepository->getById((int)$questionID);
            $this->questionsRepository->delete($question);
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect->setPath('*/*/index');
        }

        $this->messageManager->addSuccessMessage('Question deleted!');
        return $resultRedirect->setPath('*/*/index');
    }
}
