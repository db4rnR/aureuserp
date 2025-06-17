<?php

namespace App\Tests\Attributes;

use Attribute;

/**
 * Indicates which plugin is being tested.
 *
 * This attribute can be applied to test classes to specify which plugin
 * the tests are related to. This helps with organizing and filtering tests
 * by plugin.
 *
 * @example
 * ```php
 * use App\Tests\Attributes\PluginTest;
 *
 * #[PluginTest('Invoices')]
 * class InvoiceTest extends TestCase
 * {
 *     // Tests for the Invoices plugin
 * }
 * ```
 */
#[Attribute(Attribute::TARGET_CLASS)]
class PluginTestAttribute
{
    /**
     * Constructor.
     *
     * @param string $pluginName The name of the plugin being tested
     */
    public function __construct(
        public readonly string $pluginName
    ) {
    }
}
