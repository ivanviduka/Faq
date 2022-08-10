<?php
declare(strict_types=1);

namespace Favicode\Faq\Api\Data;

interface QuestionsInterface
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

    public function getId();

    public function getQuestionText();

    public function getQuestionAnswer();

    public function getCreatedAt();

    public function getUpdatedAt();

    public function getIsAnswered();

    public function getIsFaq();

    public function getProductId();

    public function getStoreId();

    public function getCustomerId();

    public function getCategoryId();

    public function setIdentity(string $id);

    public function setQuestionText(string $text);

    public function setQuestionAnswer(string $answer);

    public function setCreatedAt(string $timestamp);

    public function setUpdatedAt(string $timestamp);

    public function setIsAnswered(bool $isAnswered);

    public function setIsFaq(bool $isFaq);

    public function setProductId(string $productId);

    public function setStoreId(string $storeId);

    public function setCustomerId(string $customerId);

    public function setCategoryId(string $categoryId);
}
