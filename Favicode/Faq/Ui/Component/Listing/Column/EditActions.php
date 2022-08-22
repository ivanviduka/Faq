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
                                'faq/question/edit',
                                ['faq_id' => $item['faq_id']]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->context->getUrl(
                                'faq/question/delete',
                                ['faq_id' => $item['faq_id']]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete question'),
                                'message' => __('Are you sure you want to delete this question?')
                            ]
                        ],
                        'change' => [
                            'href' => $this->context->getUrl(
                                'faq/question/changestatus',
                                ['faq_id' => $item['faq_id']]
                            ),
                            'label' => __('Change FAQ status')
                        ]
                    ];
                } else {
                    $item[$this->getData('name')] = [
                        'answer' => [
                            'href' => $this->context->getUrl(
                                'faq/question/answer',
                                ['faq_id' => $item['faq_id']]
                            ),
                            'label' => __('Answer')
                        ],
                        'delete' => [
                            'href' => $this->context->getUrl(
                                'faq/question/delete',
                                ['faq_id' => $item['faq_id']]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete question'),
                                'message' => __('Are you sure you want to delete this question?')
                            ]
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
