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
GITHUB_REFERENCE=main
GITHUB_REPOSITORY=your-repository
SLACK_WEBHOOK=https://hooks.slack.com/services/your/slack/webhook
```

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

| Option              | Environment Variable | Description                                                                 |
| ------------------- | -------------------- | --------------------------------------------------------------------------- |
| `reference`         | `GITHUB_REFERENCE`   | The default branch or reference in your repository (e.g., 'main', 'master') |
| `repository`        | `GITHUB_REPOSITORY`  | Your repository name in 'repo' format                                       |
| `slack_webhook_url` | `SLACK_WEBHOOK`      | Your Slack webhook URL for notifications                                    |

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Credits

- [Danniel Libor](https://github.com/dlibor)
- [All contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
