<?php
declare(strict_types=1);

namespace Favicode\Faq\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class CheckAnswer extends Column
{
    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as & $item) {
            if (empty($item['question_answer'])) {
                $item['question_answer'] = __('THIS QUESTION HASN\'T BEEN ANSWERED');
            }
        }

        return $dataSource;
    }
}
