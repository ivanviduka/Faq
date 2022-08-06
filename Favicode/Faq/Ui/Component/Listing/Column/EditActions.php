<?php
declare(strict_types=1);

namespace Favicode\Faq\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class EditActions
 */
class EditActions extends Column
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
            if (isset($item['faq_id'])) {

                if ($item['is_answered']) {
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->context->getUrl(
                                'faq/questions/edit',
                                ['faq_id' => $item['faq_id']]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->context->getUrl(
                                'faq/questions/delete',
                                ['faq_id' => $item['faq_id']]
                            ),
                            'label' => __('Delete')
                        ],
                        'change' => [
                            'href' => $this->context->getUrl(
                                'faq/questions/changestatus',
                                ['faq_id' => $item['faq_id']]
                            ),
                            'label' => __('Change FAQ status')
                        ]
                    ];
                } else {
                    $item[$this->getData('name')] = [
                        'answer' => [
                            'href' => $this->context->getUrl(
                                'faq/questions/answer',
                                ['faq_id' => $item['faq_id']]
                            ),
                            'label' => __('Answer')
                        ],
                        'delete' => [
                            'href' => $this->context->getUrl(
                                'faq/questions/delete',
                                ['faq_id' => $item['faq_id']]
                            ),
                            'label' => __('Delete')
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
