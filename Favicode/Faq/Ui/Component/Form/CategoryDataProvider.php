<?php
declare(strict_types=1);

namespace Favicode\Faq\Ui\Component\Form;

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
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Favicode\Faq\Model\ResourceModel\Category\CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    )
    {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $this->collection = $collectionFactory->create();
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $data = [];
        $dataObject = $this->collection->getFirstItem();

        if ($dataObject->getId()) {
            $data[$dataObject->getId()] = $dataObject->toArray();
        }

        return $data;
    }
}