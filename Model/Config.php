<?php
declare(strict_types=1);

namespace Acronic\Cleaner\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    /**#@+
     * Acronic Cleaner setting constants
     */
    private const XML_PATH_CLEANER_ENABLE = 'ac_cleaner/general/enable';
    private const XML_PATH_CLEANER_CATALOG_CATEGORY_IDS = 'ac_cleaner/catalog_settings/category_ids';
    private const XML_PATH_CLEANER_CATALOG_REMOVE_AFTER_MOUNTS = 'ac_cleaner/catalog_settings/remove_after_months';
    private const XML_PATH_CLEANER_CRON_ERROR_EMAIL_RECIPIENT = 'ac_cleaner/cron/error_email';
    private const XML_PATH_CLEANER_CRON_ERROR_EMAIL_SENDER = 'ac_cleaner/cron/error_email_identity';
    private const XML_PATH_CLEANER_CRON_ERROR_EMAIL_TEMPLATE = 'ac_cleaner/cron/error_email_template';
    /**#@-*/

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @var int
     */
    protected int $storeId;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Set a specified store ID value
     *
     * @param int $store
     * @return $this
     */
    public function setStoreId($store): self
    {
        $this->storeId = (int)$store;
        return $this;
    }

    /**
     * Check if Acronic Cleansing is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_CLEANER_ENABLE,
            ScopeInterface::SCOPE_STORE,
            $this->storeId
        );
    }

    /**
     * Get catalog categories ids need clear
     *
     * @return array
     */
    public function getCategoryIds(): array
    {
        return (array) $this->scopeConfig->getValue(
            self::XML_PATH_CLEANER_CATALOG_CATEGORY_IDS,
            ScopeInterface::SCOPE_STORE,
            $this->storeId
        );
    }

    /**
     * Get count remove after months
     *
     * @return int
     */
    public function getRemoveAfterMonths(): int
    {
        return (int) $this->scopeConfig->getValue(
            self::XML_PATH_CLEANER_CATALOG_REMOVE_AFTER_MOUNTS,
            ScopeInterface::SCOPE_STORE,
            $this->storeId
        );
    }

    /**
     * Get cron error email recipient
     *
     * @return string|null
     */
    public function getCronErrorEmailRecipient(): ?string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CLEANER_CRON_ERROR_EMAIL_RECIPIENT,
            ScopeInterface::SCOPE_STORE,
            $this->storeId
        );
    }

    /**
     * Get cron error email sender
     *
     * @return string|null
     */
    public function getCronErrorEmailSender(): ?string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CLEANER_CRON_ERROR_EMAIL_SENDER,
            ScopeInterface::SCOPE_STORE,
            $this->storeId
        );
    }

    /**
     * Get cron error email template
     *
     * @return string|null
     */
    public function getCronErrorEmailTemplate(): ?string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CLEANER_CRON_ERROR_EMAIL_TEMPLATE,
            ScopeInterface::SCOPE_STORE,
            $this->storeId
        );
    }
}
