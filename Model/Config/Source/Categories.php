<?php
declare(strict_types=1);

namespace Acronic\Cleaner\Model\Config\Source;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Option\ArrayInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class Categories implements ArrayInterface
{
    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $categoryCollectionFactory;

    /**
     * @param CollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        CollectionFactory $categoryCollectionFactory
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array
     * @throws LocalizedException
     */
    public function toOptionArray(): array
    {
        $options = [];
        $categoryCollection = $this->categoryCollectionFactory->create();
        $categoryCollection->addAttributeToSelect('name');

        foreach ($categoryCollection as $category) {
            $options[] = [
                'value' => $category->getId(),
                'label' => $category->getName(),
            ];
        }

        return $options;
    }
}
