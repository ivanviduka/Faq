<?php
declare(strict_types=1);

namespace Favicode\Faq\Model;

use Favicode\Faq\Api\Data\CategoryInterface;
use Magento\Framework\Model\AbstractModel;

class Category extends AbstractModel implements CategoryInterface
{
    protected function _construct()
    {
        $this->_init(\Favicode\Faq\Model\ResourceModel\Category::class);
    }

    /**
     * @return int
     */
    public function getCategoryId()
    {
        return $this->getData(self::CATEGORY_ID);
    }

    /**
     * @return string
     */
    public function getCategoryName()
    {
        return $this->getData(self::CATEGORY_NAME);
    }

    /**
     * @param string $id
     * @return Category
     */
    public function setIdentity(string $id)
    {
        return $this->setData(self::CATEGORY_ID, $id);
    }

    /**
     * @param string $name
     * @return Category
     */
    public function setCategoryName(string $name)
    {
        return $this->setData(self::CATEGORY_NAME, $name);
    }
}
