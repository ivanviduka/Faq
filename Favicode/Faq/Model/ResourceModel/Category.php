<?php
declare(strict_types=1);

namespace Favicode\Faq\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Category extends AbstractDb
{

    protected function _construct()
    {
        $this->_init('favicode_faq_categories', 'faq_category_id');
    }
}
