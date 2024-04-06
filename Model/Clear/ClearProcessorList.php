<?php
declare(strict_types=1);

namespace Acronic\Cleaner\Model\Clear;

use Acronic\Cleaner\Api\ClearProcessorInterface;

class ClearProcessorList implements ClearProcessorInterface
{
    /**
     * @var array
     */
    protected array $clearProcessors;

    /**
     * @param array $clearProcessors
     */
    public function __construct(array $clearProcessors)
    {
        $this->clearProcessors = $clearProcessors;
    }

    /**
     * Process Clear
     *
     * @return void
     */
    public function processClear(): void
    {
        foreach ($this->clearProcessors as $processor) {
            if ($processor instanceof ClearProcessorInterface) {
                $processor->processClear();
            }
        }
    }
}
