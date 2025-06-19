<?php

declare(strict_types=1);

namespace App\Tests\PHPStan\Rules;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * PHPStan rule to enforce the presence of PHPDoc blocks in test functions.
 *
 * This rule checks that test functions have a PHPDoc block that describes:
 * - What is being tested
 * - The expected outcome
 * - Any special setup or conditions
 */
class TestFunctionDocBlockRule implements Rule
{
    public function getNodeType(): string
    {
        return Node\Stmt\Function_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        // Only apply this rule to test files
        if (! str_contains($scope->getFile(), '/tests/')) {
            return [];
        }

        // Only apply this rule to test functions (Pest style tests)
        if (! str_starts_with((string) $node->name->name, 'test_')) {
            return [];
        }

        $errors = [];

        // Check if the function has a PHPDoc block
        $docComment = $node->getDocComment();
        if (! $docComment instanceof Doc) {
            $errors[] = RuleErrorBuilder::message(
                'Test function should have a PHPDoc block that describes what is being tested, the expected outcome, and any special setup or conditions.'
            )->build();

            return $errors;
        }

        // Check if the PHPDoc block is comprehensive enough
        $docText = $docComment->getText();
        $minLength = 50; // Minimum length for a comprehensive PHPDoc block
        if (mb_strlen($docText) < $minLength) {
            $errors[] = RuleErrorBuilder::message(
                'Test function PHPDoc block should be comprehensive. It should describe what is being tested, the expected outcome, and any special setup or conditions.'
            )->build();
        }

        return $errors;
    }
}
