# AI Prompt Addenda Testing Guide

## 1. Overview

This document describes the testing approach for the AI Prompt Addenda feature. The test suite is designed to verify all aspects of the feature, including file hierarchy, section merging, de-duplication, versioning, conditional sections, and more.

## 2. Test Suite Structure

The test suite is located in the `tests/PromptAddenda` directory and consists of:

- `PromptAddendaTest.php`: The main test class containing all test cases
- `run-tests.php`: A simple script to run the tests without requiring a full PHPUnit setup
- `fixtures/`: Directory containing test fixtures and sample files

## 3. Running the Tests

To run the complete test suite:

```bash
php tests/PromptAddenda/run-tests.php
```

This will execute all test cases and report the results.

## 4. Test Cases

The test suite includes the following test cases:

### 4.1. File Hierarchy Tests

- `testFindAddendaFiles`: Verifies that addenda files are found in the correct order (project, parent, home)
- Tests that files closer to the project root have higher priority

### 4.2. De-duplication Tests

- `testDeduplicateAddenda`: Verifies that duplicate sections and items are properly handled
- Tests that unique items from each section are preserved
- Tests that duplicate items are removed

### 4.3. Versioned Sections Tests

- `testVersionedSections`: Verifies that section versioning works correctly
- Tests that higher versions replace lower versions of the same section
- Tests that version information is preserved in the output

### 4.4. Conditional Sections Tests

- `testConditionalSections`: Verifies that conditional sections are properly filtered
- Tests that sections are only included when conditions match
- Tests that multiple conditions are handled correctly

### 4.5. Priority Override Tests

- `testPriorityOverrides`: Verifies that priority overrides work correctly
- Tests that higher priority sections replace lower priority ones
- Tests that priority information is preserved in the output

### 4.6. Includes Tests

- `testProcessIncludes`: Verifies that file includes are processed correctly
- Tests that included content is properly merged
- Tests that circular includes are prevented

### 4.7. Caching Tests

- `testCaching`: Verifies that the caching mechanism works correctly
- Tests that cached content is returned when available
- Tests that cache is invalidated when files or conditions change

### 4.8. Project Condition Detection Tests

- `testDetectProjectConditions`: Verifies that project conditions are correctly detected
- Tests detection of PHP, Laravel, JavaScript, Vue, etc.
- Tests that environment conditions are included

### 4.9. Integration Tests

- `testGetActiveAddenda`: Verifies that the complete process works correctly
- Tests the full flow from finding files to returning consolidated addenda

## 5. Adding New Tests

To add new tests to the suite:

1. Open `tests/PromptAddenda/PromptAddendaTest.php`
2. Add a new test method with a name starting with `test`
3. Implement the test logic using PHPUnit assertions
4. Run the tests to verify your new test works correctly

Example:

```php
/**
 * Test a new feature
 */
public function testNewFeature(): void
{
    // Test setup
    $testData = "# Test Section\n- Test Item";
    
    // Call the method being tested
    $result = $this->callMethodBeingTested($testData);
    
    // Assert the expected results
    $this->assertStringContainsString('# Test Section', $result);
    $this->assertStringContainsString('- Test Item', $result);
}
```

## 6. Troubleshooting

If you encounter issues with the tests:

1. **Missing PHPUnit**: If you see an error about PHPUnit not being found, install it with:
   ```bash
   composer require --dev phpunit/phpunit
   ```

2. **Permission Issues**: If tests fail due to permission issues when creating test files:
   ```bash
   chmod -R 755 tests/PromptAddenda/fixtures
   ```

3. **Failed Assertions**: If tests fail due to assertion errors, check:
   - The implementation of the feature being tested
   - The test expectations and assertions
   - Any changes to the feature that might affect test outcomes

## 7. Continuous Integration

The test suite is designed to be run in CI environments. To integrate with your CI pipeline:

1. Add a step to run the tests:
   ```yaml
   - name: Run AI Prompt Addenda Tests
     run: php tests/PromptAddenda/run-tests.php
   ```

2. Configure the step to fail the build if tests fail (the script returns a non-zero exit code on failure)

## 8. Conclusion

The AI Prompt Addenda test suite provides comprehensive coverage of the feature's functionality. By running these tests regularly, you can ensure that the feature continues to work correctly as the codebase evolves.
