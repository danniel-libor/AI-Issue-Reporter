<?php

namespace EngagingIo\IssueReporter;

use EngagingIo\IssueReporter\Contracts\IssueReporterInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class IssueReporterServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('package', function () {
            return new IssueReporter();
        });

        $this->app->bind(IssueReporterInterface::class, IssueReporter::class);
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/issue-reporter.php' => config_path('issue-reporter.php'),
        ]);

        $loader = AliasLoader::getInstance();
        $loader->alias('IssueReporterInterface', IssueReporter::class);
    }
}
