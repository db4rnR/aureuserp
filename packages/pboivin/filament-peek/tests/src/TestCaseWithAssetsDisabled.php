<?php

declare(strict_types=1);

namespace Pboivin\FilamentPeek\Tests;

final class TestCaseWithAssetsDisabled extends TestCase
{
    protected function configurePackageProviders($app): void
    {
        TestPanelProvider::$should_load_plugin_assets = false;
    }
}
