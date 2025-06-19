<?php

declare(strict_types=1);

namespace Webkul\Timesheet;

use Webkul\Support\Console\Commands\InstallCommand;
use Webkul\Support\Console\Commands\UninstallCommand;
use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;

class TimesheetServiceProvider extends PackageServiceProvider
{
    public static string $name = 'timesheets';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(self::$name)
            ->hasTranslations()
            ->hasDependencies([
                'projects',
            ])
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command
                    ->installDependencies();
            })
            ->hasUninstallCommand(function (UninstallCommand $command): void {});
    }

    public function packageBooted(): void
    {
        //
    }
}
