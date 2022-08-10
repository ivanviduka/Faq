<?php
declare(strict_types=1);

namespace Favicode\Faq\Controller\Adminhtml\Category;

class NewAction extends \Magento\Backend\App\Action
{

    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
