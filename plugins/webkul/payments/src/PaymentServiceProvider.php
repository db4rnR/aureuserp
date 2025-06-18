<?php

declare(strict_types=1);

namespace Webkul\Payment;

use Webkul\Support\Console\Commands\InstallCommand;
use Webkul\Support\Console\Commands\UninstallCommand;
use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;

final class PaymentServiceProvider extends PackageServiceProvider
{
    public static string $name = 'payments';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(self::$name)
            ->hasTranslations()
            ->hasMigrations([
                '2025_02_10_131418_create_payments_payment_methods_table',
                '2025_02_11_101123_create_payments_payment_tokens_table',
                '2025_02_11_103602_create_payments_payment_transactions_table',
                '2025_02_12_103602_add_columns_to_account_payments_table',
            ])
            ->runsMigrations()
            ->hasDependencies([
                'accounts',
            ])
            ->hasSeeder(Database\Seeders\DatabaseSeeder::class)
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command
                    ->installDependencies()
                    ->runsMigrations()
                    ->runsSeeders();
            })
            ->hasUninstallCommand(function (UninstallCommand $command): void {});
    }
}
