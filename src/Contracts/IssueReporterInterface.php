<?php

namespace EngagingIo\IssueReporter\Contracts;

interface IssueReporterInterface
{
    /**
     * Report an exception to the configured endpoint.
     *
     * @param \Throwable $e
     * @return void
     */
    public static function report(\Throwable $e);
}
