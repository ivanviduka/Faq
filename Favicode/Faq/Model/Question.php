<?php
declare(strict_types=1);

namespace Favicode\Faq\Model;

use Favicode\Faq\Api\Data\QuestionInterface;
use Magento\Framework\Model\AbstractModel;


class Question extends AbstractModel implements QuestionInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'favicode_faq_question';

    /**
     * @var string
     */
    protected $_eventObject = 'question';

    protected function _construct()
    {
        $this->_init(\Favicode\Faq\Model\ResourceModel\Question::class);
    }

    /**
     * @return string
     */
    public function getQuestionText()
    {
        return $this->getData(self::QUESTION_TEXT);
    }

    /**
     * @return string
     */
    public function getQuestionAnswer()
    {
        return $this->getData(self::QUESTION_ANSWER);
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @return bool
     */
    public function getIsAnswered()
    {
        return $this->getData(self::IS_ANSWERED);
    }

    /**
     * @return bool
     */
    public function getIsFaq()
    {
        return $this->getData(self::IS_FAQ);
    }

    /**
     * @return int
     */
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * @return int
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * @return int
     */
    public function getCategoryId()
    {
        return $this->getData(self::CATEGORY_ID);
    }

    /**
     * @param string $id
     * @return Question
     */
    public function setIdentity(string $id)
    {

        return $this->setData(self::QUESTION_ID, $id);
    }

    /**
     * @param string $text
     * @return Question
     */
    public function setQuestionText(string $text)
    {
        return $this->setData(self::QUESTION_TEXT, $text);
    }

    /**
     * @param string $answer
     * @return Question
     */
    public function setQuestionAnswer(string $answer)
    {
        return $this->setData(self::QUESTION_ANSWER, $answer);
    }

    /**
     * @param string $timestamp
     * @return Question
     */
    public function setCreatedAt(string $timestamp)
    {
        return $this->setData(self::CREATED_AT, $timestamp);
    }

    /**
     * @param string $timestamp
     * @return Question
     */
    public function setUpdatedAt(string $timestamp)
    {
        return $this->setData(self::UPDATED_AT, $timestamp);
    }

    /**
     * @param bool $isAnswered
     * @return Question
     */
    public function setIsAnswered(bool $isAnswered)
    {
        return $this->setData(self::IS_ANSWERED, $isAnswered);
    }

    /**
     * @param bool $isFaq
     * @return Question
     */
    public function setIsFaq(bool $isFaq)
    {
        return $this->setData(self::IS_FAQ, $isFaq);
    }

    /**
     * @param string $productId
     * @return Question
     */
    public function setProductId(string $productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * @param string $storeId
     * @return Question
     */
    public function setStoreId(string $storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * @param string $customerId
     * @return Question
     */
    public function setCustomerId(string $customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * @param string $categoryId
     * @return Question
     */
    public function setCategoryId(string $categoryId)
    {
        return $this->setData(self::CATEGORY_ID, $categoryId);
    }
}
