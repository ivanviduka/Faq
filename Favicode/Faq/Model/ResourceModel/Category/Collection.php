<?php
declare(strict_types=1);

namespace Favicode\Faq\Model\ResourceModel\Category;

use Favicode\Faq\Model\Category;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            Category::class,
            \Favicode\Faq\Model\ResourceModel\Category::class
        );
    }
}
