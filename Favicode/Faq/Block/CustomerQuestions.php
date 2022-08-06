<?php
declare(strict_types=1);

namespace Favicode\Faq\Block;

use Favicode\Faq\Api\Data\QuestionsInterface;
use Favicode\Faq\Api\QuestionsRepositoryInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template\Context;

class CustomerQuestions extends \Magento\Framework\View\Element\Template
{
    protected $questionsRepository;
    protected $searchCriteriaBuilder;
    protected $sortOrderBuilder;
    protected $customerSession;
    protected $productRepository;

    private const PRODUCT_PAGE_PATH = "http://magento2.loc/catalog/product/view/id/";

    public function __construct(Context                         $context,
                                QuestionsRepositoryInterface    $questionsRepository,
                                SortOrderBuilder                $sortOrderBuilder,
                                SearchCriteriaBuilder           $searchCriteriaBuilder,
                                ProductRepository               $productRepository,
                                \Magento\Customer\Model\Session $customerSession,

                                array                           $data = []
    )
    {
        parent::__construct($context, $data);

        $this->questionsRepository = $questionsRepository;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->customerSession = $customerSession;
        $this->productRepository = $productRepository;
    }


    /**
     * @return QuestionsInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCustomerQuestions(): array
    {
        $sortOrder = $this->sortOrderBuilder->setField('created_at')->setDirection('desc')->create();

        $this->searchCriteriaBuilder
            ->addFilter('customer_id', $this->customerSession->getId())
            ->setSortOrders([$sortOrder]);

        $searchCriteria = $this->searchCriteriaBuilder->create();

        $questions = $this->questionsRepository->getList($searchCriteria)->getItems();

        return $questions;

    }

    public function getProductLink(int $productID, int $storeID): string
    {
        try {
            $product = $this->productRepository->getById($productID, false, $storeID);
        } catch (NoSuchEntityException $e) {
            error_log($e->getMessage());
        }

        return $product->getProductUrl() ?? self::PRODUCT_PAGE_PATH . $productID;
    }

}
