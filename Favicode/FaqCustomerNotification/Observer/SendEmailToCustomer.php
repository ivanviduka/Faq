<?php
declare(strict_types=1);

namespace Favicode\FaqCustomerNotification\Observer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class SendEmailToCustomer implements ObserverInterface
{
    const XML_PATH_EMAIL_ADMIN = 'trans_email/ident_general/email';
    const XML_PATH_NAME_ADMIN = 'trans_email/ident_general/name';
    const EMAIL_TEMPLATE = 'question_answered_email_template';

    /**
     * @var TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var Escaper
     */
    protected $_escaper;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        StoreManagerInterface $storeManager,
        CustomerRepositoryInterface $customerRepository,
        ScopeConfigInterface $scopeConfig,
        RequestInterface $request,
        Escaper $escaper,
        LoggerInterface $logger
    ) {
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->_escaper = $escaper;
        $this->logger = $logger;
        $this->customerRepository = $customerRepository;
        $this->request = $request;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        if($this->request->getActionName() == 'save'){
            $question = $observer->getData('question');
            $questionText = $question->getQuestionText();
            $questionAnswer = $question->getQuestionAnswer();
            $customer = $this->customerRepository->getById($question->getCustomerId());
            $username = $customer->getFirstname() . " " . $customer->getLastname();

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
                $this->logger->error($e->getMessage());
            }
        }
    }
}
