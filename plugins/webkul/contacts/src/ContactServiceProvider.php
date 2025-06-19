<?php

declare(strict_types=1);

namespace Webkul\Contact;

use Webkul\Support\Console\Commands\InstallCommand;
use Webkul\Support\Console\Commands\UninstallCommand;
use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;

class ContactServiceProvider extends PackageServiceProvider
{
    public static string $name = 'contacts';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(self::$name)
            ->hasTranslations()
            ->hasInstallCommand(function (InstallCommand $command): void {})
            ->hasUninstallCommand(function (UninstallCommand $command): void {});
    }

    public function packageBooted(): void
    {
        //
    }
}
