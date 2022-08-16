<?php
declare(strict_types=1);

namespace Favicode\FaqAdminNotification\Observer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class SendEmailToAdmin implements ObserverInterface
{

    const XML_PATH_EMAIL_RECIPIENT = 'trans_email/ident_general/email';
    const EMAIL_TEMPLATE = 'new_question_email_template';

    /**
     * @var TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Escaper
     */
    protected $_escaper;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        Escaper $escaper,
        CustomerRepositoryInterface $customerRepository,
        LoggerInterface $logger
    ) {
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->_escaper = $escaper;
        $this->customerRepository = $customerRepository;
        $this->logger = $logger;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $question = $observer->getData('question');

        try {
            $customer = $this->customerRepository->getById($question->getCustomerId());
            $name = $customer->getFirstname() . " " . $customer->getLastname();
        } catch (NoSuchEntityException|LocalizedException $e) {
            $this->logger->error($e->getMessage());
        }

        try {
            $store = $this->storeManager->getStore($question->getStoreId());
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
        }

        try {
            $sender = [
                'name' => $this->_escaper->escapeHtml($name),
                'email' => $this->_escaper->escapeHtml($customer->getEmail()),
            ];
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $templateOptions = [
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => $this->storeManager->getStore()->getId()
            ];
            $templateVars = [
                'user_name' => $this->_escaper->escapeHtml($name),
                'email' => $this->_escaper->escapeHtml($customer->getEmail()),
                'store_name' => $this->_escaper->escapeHtml($store->getName()),
                'product_id' => $this->_escaper->escapeHtml($question->getProductId())
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
            $this->logger->error($e->getMessage());
        }

    }
}
