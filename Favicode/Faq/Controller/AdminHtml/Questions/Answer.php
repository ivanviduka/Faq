<?php

namespace Favicode\Faq\Controller\AdminHtml\Questions;

use Magento\Framework\Controller\ResultFactory;

class Answer extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Favicode_Faq::questions');
        $resultPage->getConfig()->getTitle()->prepend(__('Answer Question'));

        return $resultPage;
    }
}
