<?php

namespace App\Tests\Attributes;

use Attribute;

/**
 * Marks a test as measuring performance.
 *
 * This attribute can be applied to test methods to indicate that they are
 * measuring performance aspects of the application. This helps with organizing
 * performance tests and potentially running them separately from regular tests.
 *
 * @example
 * ```php
 * use App\Tests\Attributes\Performance;
 *
 * class PerformanceTest extends TestCase
 * {
 *     #[Performance]
 *     public function test_query_performance()
 *     {
 *         // This test measures query performance
 *     }
 *
 *     #[Performance(threshold: 500)]
 *     public function test_api_response_time()
 *     {
 *         // This test measures API response time with a 500ms threshold
 *     }
 * }
 * ```
 */
#[Attribute(Attribute::TARGET_METHOD)]
class PerformanceAttribute
{
    /**
     * Constructor.
     *
     * @param int|null $threshold Maximum acceptable execution time in milliseconds (null for no threshold)
     * @param string|null $metric The specific performance metric being measured (e.g., 'query', 'response', 'memory')
     * @param bool $baseline Whether this test establishes a performance baseline
     */
    public function __construct(
        public readonly ?int $threshold = null,
        public readonly ?string $metric = null,
        public readonly bool $baseline = false
    ) {
    }
}
