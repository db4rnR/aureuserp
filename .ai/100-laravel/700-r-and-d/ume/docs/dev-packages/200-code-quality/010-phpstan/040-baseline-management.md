# PHPStan Baseline Management

## 1. Overview

PHPStan's baseline feature allows you to gradually adopt static analysis in existing projects by recording current errors and focusing only on new issues. This document explains how to effectively manage baselines in our Laravel 12 project.

## 2. What is a Baseline?

A baseline is a snapshot of current PHPStan errors that are temporarily accepted. It allows you to:

- Implement PHPStan in existing projects with legacy code
- Gradually fix errors without blocking development
- Track progress over time
- Focus on preventing new errors while addressing existing ones incrementally

## 3. Creating and Managing Baselines

### 3.1. Generating a Baseline

To create a baseline of current errors:

```bash
# Using our Composer script
composer analyze:baseline

# Or directly with PHPStan
vendor/bin/phpstan analyse --generate-baseline
```

This creates a `phpstan-baseline.neon` file in your project root.

### 3.2. Using the Baseline

Once you have a baseline, PHPStan will only report new errors not included in the baseline:

```bash
# Run analysis with baseline
composer analyze
```

### 3.3. Updating the Baseline

After fixing some errors, update the baseline to reflect your progress:

```bash
# Regenerate the baseline
composer analyze:baseline
```

### 3.4. Creating a Fresh Baseline

If your baseline becomes corrupted or you want to start fresh:

```bash
# Generate a completely fresh baseline
composer analyze:fresh-baseline
```

## 4. Baseline Workflow

Our recommended workflow for baseline management:

1. **Initial Setup**: Generate a baseline when first implementing PHPStan
2. **Daily Development**: Run PHPStan without modifying the baseline
3. **Error Fixing**: Fix errors in batches, then update the baseline
4. **Code Review**: Ensure no new errors are introduced in PRs
5. **Regular Updates**: Periodically update the baseline as errors are fixed

## 5. Advanced Baseline Techniques

### 5.1. Error Categorization

Group errors by type to tackle similar issues together:

```bash
# Extract and count error types
grep "message:" phpstan-baseline.neon | sort | uniq -c | sort -nr
```

### 5.2. Focused Baselines

For large projects, consider creating feature-specific baselines:

```bash
# Example: Analyze only specific directory
vendor/bin/phpstan analyze app/FeatureX --generate-baseline=featurex-baseline.neon
```

### 5.3. Baseline Comparison

To compare baselines before and after changes:

1. Copy current baseline: `cp phpstan-baseline.neon phpstan-baseline-old.neon`
2. Generate new baseline: `composer analyze:fresh-baseline`
3. Compare: `diff phpstan-baseline-old.neon phpstan-baseline.neon`

## 6. Tracking Progress

Monitor your progress in reducing baseline errors:

```bash
# Count errors in baseline
grep -c "message:" phpstan-baseline.neon
```

Consider tracking this metric over time to visualize progress.

## 7. CI/CD Integration

Our CI pipeline:
- Uses the committed baseline file
- Runs PHPStan without allowing new errors
- Fails if any new type errors are introduced

Example GitHub Actions configuration:

```yaml
phpstan:
  runs-on: ubuntu-latest
  steps:
    - uses: actions/checkout@v3
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.4'
    - name: Install Dependencies
      run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress
    - name: Run PHPStan
      run: composer analyze
```

## 8. Best Practices

1. **Commit Baselines**: Keep baseline files in version control
2. **Regular Updates**: Regenerate baseline at least monthly
3. **Focus on Patterns**: Address similar error types together
4. **Document Progress**: Track reduction in baseline size over time
5. **Zero-Baseline Goal**: Work towards eliminating the baseline entirely

## 9. Troubleshooting

### 9.1. Corrupted Baseline File

If the baseline file becomes corrupted with duplicate keys:

```bash
# Generate completely fresh baseline
composer analyze:fresh-baseline
```

### 9.2. Merge Conflicts

When resolving merge conflicts in baseline files:

1. Accept the version with fewer errors if possible
2. If uncertain, regenerate after the merge is complete
