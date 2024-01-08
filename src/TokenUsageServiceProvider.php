<?php

namespace Cloudstudio\TokenUsage;

use Spatie\LaravelPackageTools\Package;
use Cloudstudio\TokenUsage\Commands\TokenUsageCommand;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Cloudstudio\TokenUsage\Directives\TokenUsageDirective;

class TokenUsageServiceProvider extends PackageServiceProvider
{
    /**
     * Configure the package.
     *
     * @param Package $package
     * @return void
     */
    public function configurePackage(Package $package): void
    {
        // Register middleware
        $this->app['router']->aliasMiddleware('check-token-usage', \Cloudstudio\TokenUsage\Middleware\CheckTokenUsage::class);

        // Register directive
        TokenUsageDirective::register();

        /*
         * This class is a Package Service Provider
         *
         */
        $package
            ->name('token-usage')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_token_usage_table');
    }
}
