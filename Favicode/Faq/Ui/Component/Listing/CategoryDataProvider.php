<?php
declare(strict_types=1);

namespace Favicode\Faq\Ui\Component\Listing;

class CategoryDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param \Favicode\Faq\Model\ResourceModel\Category\CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        \Favicode\Faq\Model\ResourceModel\Category\CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $this->collection = $collectionFactory->create();
    }

    /**
     * This class can be declared with virtualType
     *
     * {@inheritdoc}
     *
     */
    public function getData()
    {
        $data = $this->collection->setOrder('faq_category_id', 'DESC')->toArray();

        return $data;
    }
}
