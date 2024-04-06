<?php
declare(strict_types=1);

namespace Acronic\Cleaner\Model\Clear\Processors;

use Acronic\Cleaner\Api\ClearProcessorInterface;
use Acronic\Cleaner\Model\Config;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Api\CategoryLinkManagementInterface;
use Psr\Log\LoggerInterface;

class CatalogProductsClear implements ClearProcessorInterface
{
    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $collectionFactory;

    /**
     * @var CategoryLinkManagementInterface
     */
    protected CategoryLinkManagementInterface $categoryLinkManagement;

    /**
     * @var Config
     */
    protected Config $config;

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @param CollectionFactory $collectionFactory
     * @param CategoryLinkManagementInterface $categoryLinkManagement
     * @param Config $config
     * @param LoggerInterface $logger
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        CategoryLinkManagementInterface $categoryLinkManagement,
        Config $config,
        LoggerInterface $logger
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->categoryLinkManagement = $categoryLinkManagement;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * Process Clear
     *
     * @return void
     */
    public function processClear(): void
    {
        $removeAfterMonths = $this->config->getRemoveAfterMonths();

        if (!is_numeric($removeAfterMonths) || $removeAfterMonths <= 0) {
            $this->logger->alert(
                "[ACRONIC ALERT SYSTEM]: NEED SET REMOVE AFTER MONTHS FOR CATALOG PRODUCT CLEANER SETTINGS"
            );
            return;
        }

        $removeAfterMonthsTime = strtotime("-{$removeAfterMonths} months");

        $this->removeProductsFromCategories(
            $this->config->getCategoryIds(),
            $removeAfterMonthsTime
        );
    }

    /**
     * Remove old products from categories
     *
     * @param $categoryIds
     * @param $removeAfterMonthsTime
     * @return void
     */
    private function removeProductsFromCategories(
        $categoryIds,
        $removeAfterMonthsTime
    ): void {
        $productCollection = $this->collectionFactory->create();
        $productCollection->addCategoriesFilter(['in' => $categoryIds]);

        foreach ($productCollection as $product) {
            $createdTimestamp = strtotime($product->getCreatedAt());
            if ($createdTimestamp <= $removeAfterMonthsTime) {
                try {
                    $this->categoryLinkManagement->assignProductToCategories(
                        $product->getSku(),
                        []
                    ); // TODO: Remove Product from Category
                    // $this->productRepository->deleteById($product->getId()); TODO: Full Remove Product
                    $this->logger->info(
                        sprintf(
                            'Product %s removed from category %s',
                            $product->getSku(),
                            $categoryIds
                        )
                    );
                } catch (\Exception $e) {
                    $this->logger->error(
                        sprintf(
                            'Error removing product %s from category %s: %s',
                            $product->getSku(),
                            $categoryIds,
                            $e->getMessage()
                        )
                    );
                }
            }
        }
    }
}
