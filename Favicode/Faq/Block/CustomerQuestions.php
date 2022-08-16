<?php
declare(strict_types=1);

namespace Favicode\Faq\Block;

use Favicode\Faq\Api\Data\QuestionInterface;
use Favicode\Faq\Api\QuestionRepositoryInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Model\Session;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template\Context;
use Psr\Log\LoggerInterface;


class CustomerQuestions extends \Magento\Framework\View\Element\Template
{
    /**
     * @var QuestionRepositoryInterface
     */
    protected $questionsRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var SortOrderBuilder
     */
    protected $sortOrderBuilder;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Context $context,
        QuestionRepositoryInterface $questionsRepository,
        SortOrderBuilder $sortOrderBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ProductRepository $productRepository,
        Session $customerSession,
        LoggerInterface $logger,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->questionsRepository = $questionsRepository;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->customerSession = $customerSession;
        $this->productRepository = $productRepository;
        $this->logger = $logger;
    }

    /**
     * @return QuestionInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCustomerQuestions(): array
    {
        $sortOrder = $this->sortOrderBuilder->setField('created_at')->setDirection('desc')->create();

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('customer_id', $this->customerSession->getId())
            ->setSortOrders([$sortOrder])
            ->create();

        $questions = $this->questionsRepository->getList($searchCriteria)->getItems();

        return $questions;

    }

    /**
     * @param int $productID
     * @param int $storeID
     * @return string
     */
    public function getProductLink(int $productID, int $storeID): string
    {
        try {
            $product = $this->productRepository->getById($productID, false, $storeID);
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
        }

        return $product->getProductUrl() ?? $this->getUrl('catalog/product/view', ['id' => $productID]);
    }

}
