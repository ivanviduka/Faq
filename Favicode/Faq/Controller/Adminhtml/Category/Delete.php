<?php
declare(strict_types=1);

namespace Favicode\Faq\Controller\Adminhtml\Category;

use Favicode\Faq\Api\CategoryRepositoryInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoriesRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Favicode_Faq::category');
    }

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        CategoryRepositoryInterface $categoriesRepository,
        LoggerInterface $logger
    ) {
        $this->categoriesRepository = $categoriesRepository;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * @return Redirect
     */
    public function execute()
    {
        $categoryId = $this->getRequest()->getParam('faq_category_id');
        $resultRedirect = $this->resultRedirectFactory->create();

        if (empty($categoryId)) {
            $this->messageManager->addErrorMessage(__('ID parameter was not given!'));
            return $resultRedirect->setPath('*/*/index');
        }

        try {
            $category = $this->categoriesRepository->getById((int)$categoryId);
            $this->categoriesRepository->delete($category);
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
            $this->messageManager->addErrorMessage(__('Category could not be deleted'));
            return $resultRedirect->setPath('*/*/index');
        }

        $this->messageManager->addSuccessMessage(__('Category deleted!'));
        return $resultRedirect->setPath('*/*/index');
    }
}
