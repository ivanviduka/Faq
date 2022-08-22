<?php
declare(strict_types=1);

namespace Favicode\Faq\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class CategoryActions extends Column
{
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as & $item) {
            $item[$this->getData('name')] = [
                'edit' => [
                    'href' => $this->context->getUrl(
                        'faq/category/edit',
                        ['faq_category_id' => $item['faq_category_id']]
                    ),
                    'label' => __('Edit')
                ],
                'delete' => [
                    'href' => $this->context->getUrl(
                        'faq/category/delete',
                        ['faq_category_id' => $item['faq_category_id']]
                    ),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete category'),
                        'message' => __('Are you sure you want to delete this category?')
                    ]
                ]
            ];
        }

        return $dataSource;
    }
}
