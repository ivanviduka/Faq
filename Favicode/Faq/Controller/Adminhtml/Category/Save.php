<?php
declare(strict_types=1);

namespace Favicode\Faq\Controller\Adminhtml\Category;


use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    protected $categoriesRepository;
    protected $category;

    public function __construct(
        \Magento\Backend\App\Action\Context             $context,
        \Favicode\Faq\Api\CategoryRepositoryInterface   $categoriesRepository,
        \Favicode\Faq\Api\Data\CategoryInterfaceFactory $category)
    {
        $this->categoriesRepository = $categoriesRepository;
        $this->category = $category;

        return parent::__construct($context);
    }


    public function execute()
    {
        $postParameters = $this->getRequest()->getParams();
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!empty($postParameters['faq_category_id'])) {
            try {
                $category = $this->categoriesRepository->getById((int)$postParameters['faq_category_id']);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['faq_category_id' => $postParameters['faq_category_id']]);
            }
        } else {
            $category = $this->category->create();
        }

        $category->setCategoryName($postParameters['category_name']);

        try {
            $this->categoriesRepository->save($category);
        } catch (CouldNotSaveException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect->setPath('*/*/edit', ['faq_category_id' => $category->getCategoryId()]);
        } catch (LocalizedException $e) {
            error_log($e->getMessage());
        }

        $this->messageManager->addSuccessMessage('Changes saved successfully!');
        return $resultRedirect->setPath('*/*/index');

    }
}
