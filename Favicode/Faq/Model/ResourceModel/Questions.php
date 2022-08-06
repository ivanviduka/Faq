<?php
declare(strict_types=1);

namespace Favicode\Faq\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Questions extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('favicode_faq', 'faq_id');
    }
}
