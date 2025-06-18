<?php

declare(strict_types=1);

namespace App\Tests\PHPStan\Rules;

use App\Tests\Attributes\PluginTest;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

/**
 * PHPStan rule to enforce the presence of required PHP attributes in test classes.
 *
 * This rule checks that test classes have the following attributes:
 * - #[Group] - To categorize the test
 * - #[CoversClass] - To indicate which class is being tested
 * - #[PluginTest] - To indicate which plugin is being tested
 */
final class TestClassAttributesRule implements Rule
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
        $hasGroup = false;
        $hasCoversClass = false;
        $hasPluginTest = false;

        // Check if the function has the required attributes
        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                $attrName = $attr->name->toString();
                if ($attrName === Group::class || $attrName === 'Group') {
                    $hasGroup = true;
                } elseif ($attrName === CoversClass::class || $attrName === 'CoversClass') {
                    $hasCoversClass = true;
                } elseif ($attrName === PluginTest::class || $attrName === 'PluginTest') {
                    $hasPluginTest = true;
                }
            }
        }

        // Add errors for missing attributes
        if (! $hasGroup) {
            $errors[] = RuleErrorBuilder::message(
                'Test function should have a #[Group] attribute to categorize the test.'
            )->build();
        }

        if (! $hasCoversClass) {
            $errors[] = RuleErrorBuilder::message(
                'Test function should have a #[CoversClass] attribute to indicate which class is being tested.'
            )->build();
        }

        if (! $hasPluginTest) {
            $errors[] = RuleErrorBuilder::message(
                'Test function should have a #[PluginTest] attribute to indicate which plugin is being tested.'
            )->build();
        }

        return $errors;
    }
}
