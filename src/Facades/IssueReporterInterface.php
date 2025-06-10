<?php

namespace EngagingIo\IssueReporter\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void report(\Throwable $e)
 * 
 * @see \EngagingIo\IssueReporter\IssueReporter
 */
class IssueReporterInterface extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \EngagingIo\IssueReporter\Contracts\IssueReporterInterface::class;
    }
}
