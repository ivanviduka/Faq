<?php
declare(strict_types=1);

namespace Favicode\Faq\Controller\Adminhtml\Category;


use Favicode\Faq\Api\CategoryRepositoryInterface;
use Favicode\Faq\Api\Data\CategoryInterfaceFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoriesRepository;

    /**
     * @var CategoryInterfaceFactory
     */
    protected $category;

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
        CategoryInterfaceFactory $category,
        LoggerInterface $logger
    ) {
        $this->categoriesRepository = $categoriesRepository;
        $this->category = $category;
        $this->logger = $logger;

        parent::__construct($context);
    }

    /**
     * @return Redirect
     */
    public function execute()
    {
        $postRequest = $this->getRequest();
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!empty($postRequest->getParam('faq_category_id'))) {
            try {
                $category = $this->categoriesRepository->getById((int)$postRequest->getParam('faq_category_id'));
            } catch (NoSuchEntityException $e) {
                $this->logger->error($e->getMessage());
                $this->messageManager->addErrorMessage(__('Category was not found'));
                return $resultRedirect->setPath('*/*/edit', ['faq_category_id' => $postRequest->getParam('faq_category_id')]);
            }
        } else {
            $category = $this->category->create();
        }

        $category->setCategoryName($postRequest->getParam('category_name'));

        try {
            $this->categoriesRepository->save($category);
        } catch (CouldNotSaveException $e) {
            $this->logger->error($e->getMessage());
            $this->messageManager->addErrorMessage(__('Category could not be saved!'));
            return $resultRedirect->setPath('*/*/edit', ['faq_category_id' => $category->getCategoryId()]);
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
        }

        $this->messageManager->addSuccessMessage(__('Changes saved successfully!'));
        return $resultRedirect->setPath('*/*/index');

    }
}
