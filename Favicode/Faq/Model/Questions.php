<?php
declare(strict_types=1);

namespace Favicode\Faq\Model;

use Favicode\Faq\Api\Data\QuestionsInterface;
use Magento\Framework\Model\AbstractModel;


class Questions extends AbstractModel implements QuestionsInterface
{

    protected function _construct()
    {
        $this->_init(\Favicode\Faq\Model\ResourceModel\Questions::class);
    }

    public function getQuestionText()
    {
        return $this->getData(self::QUESTION_TEXT);
    }

    public function getQuestionAnswer()
    {
        return $this->getData(self::QUESTION_ANSWER);
    }

    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    public function getIsAnswered()
    {
        return $this->getData(self::IS_ANSWERED);
    }

    public function getIsFaq()
    {
        return $this->getData(self::IS_FAQ);
    }

    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    public function getCategoryId()
    {
        return $this->getData(self::CATEGORY_ID);
    }

    public function setIdentity(string $id)
    {

        return $this->setData(self::QUESTION_ID, $id);
    }

    public function setQuestionText(string $text)
    {
        return $this->setData(self::QUESTION_TEXT, $text);
    }

    public function setQuestionAnswer(string $answer)
    {
        return $this->setData(self::QUESTION_ANSWER, $answer);
    }

    public function setCreatedAt(string $timestamp)
    {
        return $this->setData(self::CREATED_AT, $timestamp);
    }

    public function setUpdatedAt(string $timestamp)
    {
        return $this->setData(self::UPDATED_AT, $timestamp);
    }

    public function setIsAnswered(bool $isAnswered)
    {
        return $this->setData(self::IS_ANSWERED, $isAnswered);
    }

    public function setIsFaq(bool $isFaq)
    {
        return $this->setData(self::IS_FAQ, $isFaq);
    }

    public function setProductId(string $productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    public function setStoreId(string $storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    public function setCustomerId(string $customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    public function setCategoryId(string $categoryId)
    {
        return $this->setData(self::CATEGORY_ID, $categoryId);
    }
}
