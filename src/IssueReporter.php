<?php

namespace EngagingIo\IssueReporter;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

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
        $payload = self::buildBasePayload($e);

        if ($e instanceof RequestException && isset($e->response->transferStats)) {
            $request = $e->response->transferStats->getRequest();
            $payload['request'] = json_encode([
                'headers' => $request->getHeaders(),
                'body' => (string) $request->getBody(),
            ]);
        }

        self::sendReport($payload);
    }

    /**
     * Build the base payload for the report.
     *
     * @param \Throwable $e
     * @return array
     */
    private static function buildBasePayload(\Throwable $e): array
    {
        return [
            'environment' => app()->environment(),
            'slackWebhookUrl' => config('issue-reporter.slack_webhook_url'),
            'reference' => config('issue-reporter.reference'),
            'repository' => config('issue-reporter.repository'),
            'errorMessage' => $e->getMessage(),
            'stackTrace' => $e->getTraceAsString(),
        ];
    }

    /**
     * Send the report to the configured endpoint.
     *
     * @param array $payload
     * @return void
     */
    private static function sendReport(array $payload): void
    {
        $endpoint = 'https://eio-ai-reporter.ec7.co/report';
        Http::post($endpoint, $payload);
    }
}
