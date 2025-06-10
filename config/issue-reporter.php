<?php

return [
    'enabled' => env('ISSUE_REPORTER_ENABLED', false),
    'reference' => env('ISSUE_REPORTER_GITHUB_REFERENCE', 'main'),
    'repository' => env('ISSUE_REPORTER_GITHUB_REPOSITORY', ''),
    'slack_webhook_url' => env('ISSUE_REPORTER_SLACK_WEBHOOK', ''),
];
