<?php
declare(strict_types=1);

namespace Favicode\Faq\Api\Data;

interface QuestionInterface
{

    const QUESTION_ID = 'question_id';
    const QUESTION_TEXT = 'question_text';
    const QUESTION_ANSWER = 'question_answer';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const IS_ANSWERED = 'is_answered';
    const IS_FAQ = 'is_faq';
    const CUSTOMER_ID = 'customer_id';
    const PRODUCT_ID = 'product_id';
    const STORE_ID = 'store_id';
    const CATEGORY_ID = 'faq_category_id';

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getQuestionText();

    /**
     * @return string
     */
    public function getQuestionAnswer();

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @return string
     */
    public function getUpdatedAt();

    /**
     * @return bool
     */
    public function getIsAnswered();

    /**
     * @return bool
     */
    public function getIsFaq();

    /**
     * @return int
     */
    public function getProductId();

    /**
     * @return int
     */
    public function getStoreId();

    /**
     * @return int
     */
    public function getCustomerId();

    /**
     * @return int
     */
    public function getCategoryId();

    /**
     * @param string $id
     * @return mixed
     */
    public function setIdentity(string $id);

    /**
     * @param string $text
     * @return mixed
     */
    public function setQuestionText(string $text);

    /**
     * @param string $answer
     * @return mixed
     */
    public function setQuestionAnswer(string $answer);

    /**
     * @param string $timestamp
     * @return mixed
     */
    public function setCreatedAt(string $timestamp);

    /**
     * @param string $timestamp
     * @return mixed
     */
    public function setUpdatedAt(string $timestamp);

    /**
     * @param bool $isAnswered
     * @return mixed
     */
    public function setIsAnswered(bool $isAnswered);

    /**
     * @param bool $isFaq
     * @return mixed
     */
    public function setIsFaq(bool $isFaq);


    /**
     * @param string $productId
     * @return mixed
     */
    public function setProductId(string $productId);

    /**
     * @param string $storeId
     * @return mixed
     */
    public function setStoreId(string $storeId);

    /**
     * @param string $customerId
     * @return mixed
     */
    public function setCustomerId(string $customerId);

    /**
     * @param string $categoryId
     * @return mixed
     */
    public function setCategoryId(string $categoryId);
}
