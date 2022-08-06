<?php
declare(strict_types=1);

namespace Favicode\Faq\Model\Answers;

class AnswerList implements \Magento\Framework\Option\ArrayInterface
{

    public function toOptionArray(): array
    {
        $options = [];
        $options[] = ['label' => 'Answered', 'value' => '1'];
        $options[] = ['label' => 'Unanswered', 'value' => '0'];
        return $options;
    }
}
