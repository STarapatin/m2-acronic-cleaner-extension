<?php
declare(strict_types=1);

namespace Acronic\Cleaner\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Frequency implements ArrayInterface
{
    /**#@+
     * Constants for keys of data array.
     */
    public const CRON_DAILY = 'D';
    public const CRON_WEEKLY = 'W';
    public const CRON_MONTHLY = 'M';
    /**#@-*/

    /**
     * @var array
     */
    private array $options;

    /**
     * @param array $options
     */
    public function __construct(
        array $options = []
    ) {
        $this->options = $options;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        if (!$this->options) {
            $this->options = [
                ['label' => __('Daily'), 'value' => self::CRON_DAILY],
                ['label' => __('Weekly'), 'value' => self::CRON_WEEKLY],
                ['label' => __('Monthly'), 'value' => self::CRON_MONTHLY]
            ];
        }

        return $this->options;
    }
}
