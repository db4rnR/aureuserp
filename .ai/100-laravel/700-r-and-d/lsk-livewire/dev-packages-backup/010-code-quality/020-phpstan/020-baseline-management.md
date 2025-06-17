# PHPStan Baseline Management
# PHPStan Baseline Management

## 1. What is a Baseline?

A baseline is a snapshot of current PHPStan errors that are accepted temporarily. It allows you to:

- Implement PHPStan in existing projects with legacy code
- Gradually fix errors without blocking development
- Track progress over time
- Focus on preventing new errors while addressing existing ones incrementally

## 2. Creating a Baseline

To create a baseline of current errors:
Managing PHPStan baselines effectively is crucial for maintaining code quality in large projects. This document outlines our approach to baseline management.

## What is a Baseline?

A baseline is a snapshot of all current PHPStan errors that we commit to fixing over time. It allows us to:

1. Start using PHPStan at high levels without fixing all errors immediately
2. Track our progress as we gradually fix issues
3. Ensure no new errors are introduced in the codebase

## Our Baseline Workflow

### 1. Initial Baseline Generation

When setting up PHPStan or when significant changes occur, generate a fresh baseline:

```bash
composer analyze:fresh-baseline
```

This creates a clean baseline file (`phpstan-baseline.neon`) with all current errors.

### 2. Regular Analysis

For day-to-day development:

```bash
composer analyze
```

This runs PHPStan and reports only new errors not in the baseline.

### 3. Fixing Errors

When fixing errors:

1. Run analysis to identify errors: `composer analyze`
2. Fix the issues in your code
3. Run analysis again to verify fixes
4. Regenerate the baseline to update progress: `composer analyze:fresh-baseline`
5. Commit both code fixes and updated baseline

### 4. Tracking Progress

Monitor baseline shrinkage over time:

```bash
# Count errors in baseline
grep -c "message:" phpstan-baseline.neon
```
# PHPStan Baseline Management

This guide covers how to effectively use PHPStan's baseline feature to manage and gradually fix errors in our Laravel 12 project.

## 1. Understanding Baselines

A PHPStan baseline is a file that records current errors in your codebase, allowing you to:

- Fix errors incrementally without blocking CI/CD pipelines
- Prevent new errors while working on existing ones
- Track progress in error reduction over time

The baseline file (`phpstan-baseline.neon`) contains all currently ignored errors that PHPStan would otherwise report.

## 2. Creating a Baseline

### 2.1. Initial Baseline Generation

Generate your first baseline:
### 5. CI Integration

Our CI pipeline:
- Uses the committed baseline file
- Runs PHPStan without allowing new errors
- Fails if any new type errors are introduced

## Advanced Baseline Techniques

### Error Categorization

Group errors by type to tackle similar issues together:

```bash
# Extract and count error types
grep "message:" phpstan-baseline.neon | sort | uniq -c | sort -nr
```

### Focused Baselines

For large projects, consider creating feature-specific baselines:

```bash
# Example: Analyze only specific directory
vendor/bin/phpstan analyze app/FeatureX --generate-baseline=featurex-baseline.neon
```

### Baseline Comparison

To compare baselines before and after changes:

1. Copy current baseline: `cp phpstan-baseline.neon phpstan-baseline-old.neon`
2. Generate new baseline: `composer analyze:fresh-baseline`
3. Compare: `diff phpstan-baseline-old.neon phpstan-baseline.neon`

## Best Practices

1. **Regular Updates**: Regenerate baseline at least monthly
2. **Commit Baselines**: Keep baselines in version control
3. **Focus on Patterns**: Address similar error types together
4. **Document Progress**: Track reduction in baseline size over time
5. **Zero-Baseline Goal**: Work towards eliminating the baseline entirely

## Troubleshooting

### Corrupted Baseline File

If the baseline file becomes corrupted with duplicate keys:

```bash
# Generate completely fresh baseline
composer analyze:fresh-baseline
```

### Merge Conflicts

When resolving merge conflicts in baseline files:

1. Accept the version with fewer errors if possible
2. If uncertain, regenerate after the merge is complete
