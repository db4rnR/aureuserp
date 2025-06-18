<?php

declare(strict_types=1);

namespace Pboivin\FilamentPeek\Tests;

use Illuminate\Support\Facades\Config;

final class TestCaseWithPreviewUrl extends TestCase
{
    protected function configurePackageProviders($app): void
    {
        Config::set('filament-peek.internalPreviewUrl.enabled', true);

        TestPanelProvider::$should_load_plugin_assets = false;
    }
}
