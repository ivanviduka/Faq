<?php
declare(strict_types=1);

namespace Favicode\Faq\Controller\Adminhtml\Category;

use Magento\Framework\Controller\ResultFactory;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * Edit News action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Favicode_Faq::category');
        $resultPage->getConfig()->getTitle()->prepend(__('Edit Category'));

        return $resultPage;
    }
}
