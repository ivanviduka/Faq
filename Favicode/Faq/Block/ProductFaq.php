<?php
declare(strict_types=1);


namespace Favicode\Faq\Block;

use Favicode\Faq\Api\Data\QuestionsInterface;
use Favicode\Faq\Api\QuestionsRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\View\Element\Template\Context;

class ProductFaq extends \Magento\Framework\View\Element\Template
{
    protected $questionsRepository;
    protected $searchCriteriaBuilder;
    protected $sortOrderBuilder;
    protected $customerSession;

    public function __construct(Context                         $context,
                                QuestionsRepositoryInterface    $questionsRepository,
                                SortOrderBuilder                $sortOrderBuilder,
                                SearchCriteriaBuilder           $searchCriteriaBuilder,
                                \Magento\Customer\Model\Session $customerSession,
                                array                           $data = []
    )
    {
        parent::__construct($context, $data);

        $this->questionsRepository = $questionsRepository;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->customerSession = $customerSession;
    }

    /**
     * @return QuestionsInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductFaq()
    {
        $sortOrder = $this->sortOrderBuilder->setField('created_at')->setDirection('desc')->create();

        $this->searchCriteriaBuilder
            ->addFilter('product_id', $this->getRequest()->getParam('id'))
            ->addFilter('store_id', $this->_storeManager->getStore()->getId())
            ->addFilter('is_faq', 1)
            ->setSortOrders([$sortOrder]);

        $searchCriteria = $this->searchCriteriaBuilder->create();

        $questions = $this->questionsRepository->getList($searchCriteria)->getItems();

        return $questions;
    }

    public function getProductId()
    {
        return $this->getRequest()->getParam('id');
    }

    public function isLoggedIn()
    {
        return $this->customerSession->isLoggedIn();
    }

}
