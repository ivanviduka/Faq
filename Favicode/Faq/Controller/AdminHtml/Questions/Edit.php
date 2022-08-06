<?php
declare(strict_types=1);
namespace Favicode\Faq\Controller\AdminHtml\Questions;

use Magento\Framework\Controller\ResultFactory;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Favicode_Faq::questions');
        $resultPage->getConfig()->getTitle()->prepend(__('Question details'));

        return $resultPage;
    }
}
