<?php
declare(strict_types=1);

namespace Favicode\Faq\Ui\Component\Listing;

use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Exception\NoSuchEntityException;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param \Favicode\Faq\Model\ResourceModel\Questions\CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Favicode\Faq\Model\ResourceModel\Questions\CollectionFactory $collectionFactory,
        ProductRepository $productRepository,
        array $meta = [],
        array $data = []
    )
    {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $this->collection = $collectionFactory->create();
        $this->productRepository = $productRepository;
    }

    /**
     * This class can be declared with virtualType
     *
     * {@inheritdoc}
     * @throws NoSuchEntityException
     */
    public function getData()
    {
        $data = $this->collection->setOrder('created_at', 'DESC')
            ->setOrder('is_answered', 'ASC')
            ->toArray();


        foreach ($data['items'] as & $question) {
            $product = $this->productRepository->getById($question['product_id']);
            $question['product_name'] = $product->getName();
        }
        return $data;
    }
}
