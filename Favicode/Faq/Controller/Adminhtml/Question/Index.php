<?php
declare(strict_types=1);

namespace Favicode\Faq\Controller\Adminhtml\Question;

use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Backend\App\Action
{
    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Favicode_Faq::question');
    }

    /**
     * Index action
     * @return Page
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->setActiveMenu('Favicode_Faq::question');
        $resultPage->getConfig()->getTitle()->prepend(__('Question'));

        return $resultPage;
    }

}
