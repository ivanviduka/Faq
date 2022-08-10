<?php
declare(strict_types=1);

namespace Favicode\Faq\Controller\Adminhtml\Category;

use Magento\Framework\Exception\LocalizedException;

class Delete extends \Magento\Backend\App\Action
{
    protected $categoriesRepository;

    public function __construct(
        \Magento\Backend\App\Action\Context            $context,
        \Favicode\Faq\Api\CategoryRepositoryInterface $categoriesRepository
    )
    {
        $this->categoriesRepository = $categoriesRepository;
        return parent::__construct($context);
    }


    public function execute()
    {
        $categoryId = $this->getRequest()->getParam('faq_category_id');
        $resultRedirect = $this->resultRedirectFactory->create();

        if (empty($categoryId)) {
            $this->messageManager->addErrorMessage("ID parameter was not given!");
            return $resultRedirect->setPath('*/*/index');
        }

        try {
            $category = $this->categoriesRepository->getById((int)$categoryId);
            $this->categoriesRepository->delete($category);
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect->setPath('*/*/index');
        }

        $this->messageManager->addSuccessMessage('Category deleted!');
        return $resultRedirect->setPath('*/*/index');
    }
}
