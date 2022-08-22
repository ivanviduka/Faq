<?php
declare(strict_types=1);

namespace Favicode\Faq\Model\ResourceModel\Question;

use Favicode\Faq\Model\Question;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            Question::class,
            \Favicode\Faq\Model\ResourceModel\Question::class
        );
    }
}
