<?php
declare(strict_types=1);

namespace Acronic\Cleaner\Cron;

use Acronic\Cleaner\Model\Clear\ClearProcessorList;
use Acronic\Cleaner\Model\Config;
use Magento\Store\Model\StoreManagerInterface;

class Clear
{
    /**
     * @var Config
     */
    protected Config $config;

    /**
     * @var ClearProcessorList
     */
    protected ClearProcessorList $processorList;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @param ClearProcessorList $processorList
     * @param StoreManagerInterface $storeManager
     * @param Config $config
     */
    public function __construct(
        ClearProcessorList $processorList,
        StoreManagerInterface $storeManager,
        Config $config
    ) {
        $this->processorList = $processorList;
        $this->storeManager = $storeManager;
        $this->config = $config;
    }

    /**
     * Execute cleaner
     *
     * @return void
     */
    public function execute(): void
    {
        $stores = $this->storeManager->getStores(true);

        foreach ($stores as $store) {
            $this->config->setStoreId($store->getId());

            if ($this->config->isEnabled()) {
                $this->processorList->processClear();
            }
        }
    }
}
