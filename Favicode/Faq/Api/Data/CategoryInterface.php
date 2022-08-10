<?php
declare(strict_types=1);

namespace Favicode\Faq\Api\Data;

interface CategoryInterface
{
    const CATEGORY_ID = 'faq_category_id';
    const CATEGORY_NAME = 'category_name';

    public function getCategoryId();

    public function getCategoryName();

    public function setIdentity(string $id);

    public function setCategoryName(string $name);


}
