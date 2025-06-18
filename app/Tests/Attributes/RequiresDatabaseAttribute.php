<?php

declare(strict_types=1);

namespace App\Tests\Attributes;

use Attribute;

/**
 * Indicates that a test requires a database connection.
 *
 * This attribute can be applied to test classes or methods to specify that
 * they require a database connection. This helps with organizing tests and
 * potentially optimizing test execution by grouping database-dependent tests.
 *
 * @example
 * ```php
 * use App\Tests\Attributes\RequiresDatabase;
 *
 * #[RequiresDatabase]
 * class DatabaseTest extends TestCase
 * {
 *     // Tests that require a database connection
 * }
 *
 * // Or on a specific method
 * class MixedTest extends TestCase
 * {
 *     #[RequiresDatabase]
 *     public function test_database_operation()
 *     {
 *         // This test requires a database connection
 *     }
 *
 *     public function test_no_database()
 *     {
 *         // This test doesn't require a database connection
 *     }
 * }
 * ```
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final readonly class RequiresDatabaseAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $refreshAfterTest  Whether to refresh the database after the test
     * @param  bool  $useTransactions  Whether to use database transactions for test isolation
     */
    public function __construct(
        public bool $refreshAfterTest = true,
        public bool $useTransactions = true
    ) {}
}
