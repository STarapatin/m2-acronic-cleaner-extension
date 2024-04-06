<?php
declare(strict_types=1);

namespace Acronic\Cleaner\Api;

interface ClearProcessorInterface
{
    /**
     * Process Clear
     *
     * @return void
     */
    public function processClear(): void;
}
