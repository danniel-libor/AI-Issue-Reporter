# Laravel AI Issue Reporter

[![Latest Version on Packagist](https://img.shields.io/packagist/v/engaging-io/issue-reporter.svg?style=flat-square)](https://packagist.org/packages/engaging-io/issue-reporter)
[![Total Downloads](https://img.shields.io/packagist/dt/engaging-io/issue-reporter.svg?style=flat-square)](https://packagist.org/packages/engaging-io/issue-reporter)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg?style=flat-square)](LICENSE)

A Laravel package for reporting exceptions to a designated endpoint with additional context, which can be used for automatic issue creation with AI assistance.

## Features

- Report any exception with comprehensive context data
- Special handling for HTTP request exceptions (with additional request data)
- Integration with Slack notifications
- Connection to your GitHub repository for automated issue tracking
- Easy to configure and use in any Laravel application

## Requirements

- PHP 7.3+
- Laravel 8.0+ / 9.0+ / 10.0+ / 11.0+ / 12.0+

## Installation

You can install the package via Composer:

```bash
composer require engaging-io/issue-reporter
```

The package will automatically register its service provider if you're using Laravel 5.5+.

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="EngagingIo\IssueReporter\IssueReporterServiceProvider"
```

This will create a `config/issue-reporter.php` file where you can modify the package settings.

### Environment Variables

Add these variables to your `.env` file:

```
ISSUE_REPORTER_ENABLED=false  # Set to true in production environments only
ISSUE_REPORTER_GITHUB_REFERENCE=main
ISSUE_REPORTER_GITHUB_REPOSITORY=your-repository
ISSUE_REPORTER_SLACK_WEBHOOK=https://hooks.slack.com/services/your/slack/webhook
```

> **Note:** Always keep `ISSUE_REPORTER_ENABLED` set to `false` for local development environments to prevent unnecessary reporting of development exceptions.

## Usage

### Basic Exception Reporting

```php
use EngagingIo\IssueReporter\Facades\IssueReporter;

try {
    // Your code that might throw an exception
} catch (\Throwable $e) {
    IssueReporter::report($e);

    // Then handle the exception as you normally would
}
```

### In Exception Handlers

You can easily use the package in your Laravel exception handler:

```php
// app/Exceptions/Handler.php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use EngagingIo\IssueReporter\Facades\IssueReporter;
use Throwable;

class Handler extends ExceptionHandler
{
    // ...

    public function report(Throwable $exception)
    {
        // You can add conditions to determine which exceptions to report
        if ($this->shouldReportToIssueReporter($exception)) {
            IssueReporter::report($exception);
        }

        parent::report($exception);
    }

    private function shouldReportToIssueReporter(Throwable $exception)
    {
        // Implement your logic to determine which exceptions to report
        return !$this->inExcludeList($exception) && app()->environment('production');
    }

    // ...
}
```

### Using bootstrap/app.php (Laravel 10+)

For Laravel 10 and above, you can also integrate the issue reporter in your `bootstrap/app.php` file:

```php
// bootstrap/app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use EngagingIo\IssueReporter\Facades\IssueReporter;
// ...

return Application::configure(basePath: dirname(__DIR__))
    // ...
    ->withExceptions(function (Exceptions $exceptions) {
        // This section captures all types of exceptions.
        $exceptions->reportable(fn (\Throwable $e) => IssueReporter::report($e));
    })->create();
```

### HTTP RequestException Handling

The package automatically detects if the exception is an HTTP `RequestException` and includes relevant request data in the report:

- Request headers
- Request body
- Response data (if available)

This additional information helps with debugging API-related issues.

## Security

The package sends exception data to a designated endpoint. Make sure:

1. Your Slack webhook URL is kept secure
2. Your API endpoint is secured with proper authentication
3. Sensitive data in error reports is properly sanitized

## Configuration Options

| Option              | Environment Variable               | Description                                                                                      |
| ------------------- | ---------------------------------- | ------------------------------------------------------------------------------------------------ |
| `enabled`           | `ISSUE_REPORTER_ENABLED`           | Whether the issue reporter is enabled (true/false). Should be set to false for local development |
| `reference`         | `ISSUE_REPORTER_GITHUB_REFERENCE`  | The default branch or reference in your repository (e.g., 'main', 'master')                      |
| `repository`        | `ISSUE_REPORTER_GITHUB_REPOSITORY` | Your repository name in 'repo' format                                                            |
| `slack_webhook_url` | `ISSUE_REPORTER_SLACK_WEBHOOK`     | Your Slack webhook URL for notifications                                                         |

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Credits

- [Danniel Libor](https://github.com/dlibor)
- [All contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
