<?php
declare(strict_types=1);

namespace Acronic\Cleaner\Model\Config\Backend;

use Acronic\Cleaner\Model\Config\Source\Frequency;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value
    as MagentoConfigValue;
use Magento\Framework\App\Config\ValueFactory
    as MagentoConfigValueFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class Cron extends MagentoConfigValue
{
    /**
     * Cron string path
     */
    private const CRON_STRING_PATH = 'crontab/default/jobs/ac_cleaner/schedule/cron_expr';

    /**
     * Cron model path
     */
    private const CRON_MODEL_PATH = 'crontab/default/jobs/ac_cleaner/run/model';

    /**
     * @var MagentoConfigValueFactory
     */
    protected MagentoConfigValueFactory $_configValueFactory;

    /**
     * @var string
     */
    protected string $_runModelPath = '';

    /**
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param MagentoConfigValueFactory $configValueFactory
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param string $runModelPath
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        MagentoConfigValueFactory $configValueFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        string $runModelPath = '',
        array $data = []
    ) {
        $this->_runModelPath = $runModelPath;
        $this->_configValueFactory = $configValueFactory;
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * After save cron task setting
     *
     * @return Cron
     * @throws \Exception
     */
    public function afterSave(): self
    {
        $time = $this->getData('groups/cron/fields/time/value');
        $frequency = $this->getData('groups/cron/fields/frequency/value');

        $cronExprArray = [
            (int)$time[1], //Minute
            (int)$time[0], //Hour
            $frequency == Frequency::CRON_MONTHLY ? '1' : '*', //Day of the Month
            '*', //Month of the Year
            $frequency == Frequency::CRON_WEEKLY ? '1' : '*', //Day of the Week
        ];

        $cronExprString = join(' ', $cronExprArray);

        try {
            $this->_configValueFactory->create()->load(
                self::CRON_STRING_PATH,
                'path'
            )->setValue(
                $cronExprString
            )->setPath(
                self::CRON_STRING_PATH
            )->save();
            $this->_configValueFactory->create()->load(
                self::CRON_MODEL_PATH,
                'path'
            )->setValue(
                $this->_runModelPath
            )->setPath(
                self::CRON_MODEL_PATH
            )->save();
        } catch (\Exception $e) {
            throw new \Exception(__('We can\'t save the cron expression.'));
        }

        return parent::afterSave();
    }
}
