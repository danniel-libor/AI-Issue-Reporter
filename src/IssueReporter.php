<?php

namespace EngagingIo\IssueReporter;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class IssueReporter implements \EngagingIo\IssueReporter\Contracts\IssueReporterInterface
{
    /**
     * Report an exception to the configured endpoint.
     *
     * @param \Throwable $e
     * @return void
     */
    public static function report(\Throwable $e)
    {
        if ($e instanceof RequestException) {
            $request = $e->response->transferStats->getRequest();

            Http::post('https://5895-104-37-5-2.ngrok-free.app/report', [
                'environment' => app()->environment(),
                'slackWebhookUrl' => config('issue-reporter.slack_webhook_url'),
                'reference' => config('issue-reporter.reference'),
                'repository' => config('issue-reporter.repository'),
                'errorMessage' => $e->getMessage(),
                'stackTrace' => $e->getTraceAsString(),
                'request' => json_encode([
                    'headers' => $request->getHeaders(),
                    'body' => (string) $request->getBody(),
                ]),
            ]);
        } else {
            Http::post('https://5895-104-37-5-2.ngrok-free.app/report', [
                'environment' => app()->environment(),
                'slackWebhookUrl' => config('issue-reporter.slack_webhook_url'),
                'reference' => config('issue-reporter.reference'),
                'repository' => config('issue-reporter.repository'),
                'errorMessage' => $e->getMessage(),
                'stackTrace' => $e->getTraceAsString(),
            ]);
        }
    }
}
