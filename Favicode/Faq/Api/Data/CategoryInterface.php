<?php
declare(strict_types=1);

namespace Favicode\Faq\Api\Data;

interface CategoryInterface
{
    const CATEGORY_ID = 'faq_category_id';
    const CATEGORY_NAME = 'category_name';

    /**
     * @return int
     */
    public function getCategoryId();

    /**
     * @return string
     */
    public function getCategoryName();

    /**
     * @param string $id
     * @return mixed
     */
    public function setIdentity(string $id);


    /**
     * @param string $name
     * @return mixed
     */
    public function setCategoryName(string $name);


}
