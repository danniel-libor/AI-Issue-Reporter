<?php

namespace EngagingIo\IssueReporter\Facades;

use EngagingIo\IssueReporter\Contracts\IssueReporterInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void report(\Throwable $e)
 *
 * @see \EngagingIo\IssueReporter\IssueReporter
 */
class IssueReporter extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return IssueReporterInterface::class;
    }
}
