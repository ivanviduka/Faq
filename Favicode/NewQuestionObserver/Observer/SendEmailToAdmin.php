<?php
declare(strict_types=1);

namespace Favicode\NewQuestionObserver\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class SendEmailToAdmin implements ObserverInterface
{
    const XML_PATH_EMAIL_RECIPIENT = 'trans_email/ident_general/email';
    const EMAIL_TEMPLATE = 'new_question_email_template';
    protected $_transportBuilder;
    protected $inlineTranslation;
    protected $scopeConfig;
    protected $storeManager;
    protected $_escaper;

    public function __construct(
        \Magento\Framework\Mail\Template\TransportBuilder  $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface         $storeManager,
        \Magento\Framework\Escaper                         $escaper
    )
    {
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->_escaper = $escaper;
    }


    public function execute(Observer $observer)
    {
        $customer = $observer->getData('customer');
        $productId = $observer->getData('product_id');
        $store = $observer->getData('store');

        try {

            $sender = [
                'name' => $this->_escaper->escapeHtml($customer->getFirstName()),
                'email' => $this->_escaper->escapeHtml($customer->getEmail()),
            ];
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $templateOptions = [
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => $this->storeManager->getStore()->getId()
            ];
            $templateVars = [
                'user_name' => $this->_escaper->escapeHtml($customer->getName()),
                'email' => $this->_escaper->escapeHtml($customer->getEmail()),
                'store_name' => $this->_escaper->escapeHtml($store->getName()),
                'product_id' => $this->_escaper->escapeHtml($productId)
            ];

            $this->inlineTranslation->suspend();

            $transport = $this->_transportBuilder->setTemplateIdentifier(self::EMAIL_TEMPLATE)
                ->setTemplateOptions($templateOptions)
                ->setTemplateVars($templateVars)
                ->setFromByScope($sender, $storeScope)
                ->addTo($this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope))
                ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();

        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

    }
}
