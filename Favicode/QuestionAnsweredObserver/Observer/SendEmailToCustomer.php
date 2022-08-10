<?php
declare(strict_types=1);

namespace Favicode\QuestionAnsweredObserver\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\ScopeInterface;

class SendEmailToCustomer implements ObserverInterface
{
    const XML_PATH_EMAIL_ADMIN = 'trans_email/ident_general/email';
    const XML_PATH_NAME_ADMIN = 'trans_email/ident_general/name';
    const EMAIL_TEMPLATE = 'question_answered_email_template';

    protected $_transportBuilder;
    protected $inlineTranslation;
    protected $storeManager;
    protected $scopeConfig;
    protected $_escaper;

    public function __construct(
        \Magento\Framework\Mail\Template\TransportBuilder  $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Store\Model\StoreManagerInterface         $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Escaper                         $escaper
    )
    {
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->_escaper = $escaper;
    }

    public function execute(Observer $observer)
    {
        $customer = $observer->getData('customer');
        $username = $customer->getFirstname() . " " . $customer->getLastname();
        $questionText = $observer->getData('question');
        $questionAnswer = $observer->getData('answer');
        $storeScope = ScopeInterface::SCOPE_STORE;

        try {

            $sender = [
                'name' => $this->scopeConfig->getValue(self::XML_PATH_NAME_ADMIN, $storeScope),
                'email' => $this->scopeConfig->getValue(self::XML_PATH_EMAIL_ADMIN, $storeScope)
            ];

            $templateOptions = [
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => $this->storeManager->getStore()->getId()
            ];
            $templateVars = [
                'user_name' => $this->_escaper->escapeHtml($username),
                'question_text' => $this->_escaper->escapeHtml($questionText),
                'question_answer' => $this->_escaper->escapeHtml($questionAnswer)
            ];

            $this->inlineTranslation->suspend();

            $transport = $this->_transportBuilder->setTemplateIdentifier(self::EMAIL_TEMPLATE)
                ->setTemplateOptions($templateOptions)
                ->setTemplateVars($templateVars)
                ->setFromByScope($sender, $storeScope)
                ->addTo($customer->getEmail())
                ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();

        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
}
