<?php
declare(strict_types=1);

namespace Favicode\Faq\Model\ResourceModel\Questions;

use Favicode\Faq\Model\Questions;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            Questions::class,
            \Favicode\Faq\Model\ResourceModel\Questions::class
        );
    }
}
