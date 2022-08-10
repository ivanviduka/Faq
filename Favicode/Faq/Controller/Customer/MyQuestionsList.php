<?php
declare(strict_types=1);

namespace Favicode\Faq\Controller\Customer;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;

class MyQuestionsList implements ActionInterface, HttpGetActionInterface
{
    private $resultPageFactory;
    private $customerSession;
    private $redirect;

    public function __construct(\Magento\Framework\View\Result\PageFactory $resultPageFactory,
                                RedirectFactory                            $redirectFactory,
                                \Magento\Customer\Model\Session            $customerSession)
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->customerSession = $customerSession;
        $this->redirect = $redirectFactory;
    }

    public function execute()
    {
        if (!$this->customerSession->isLoggedIn()) return $this->redirect->create()->setPath('customer/account/login');

        return $this->resultPageFactory->create();
    }
}
