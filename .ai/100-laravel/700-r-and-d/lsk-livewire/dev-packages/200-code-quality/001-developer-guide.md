# Code Quality Developer Guide

## 1. Introduction

This guide explains our code quality standards and how to use our tools effectively. Following these practices ensures
consistent, maintainable, and high-quality code across the project.

## 2. Code Quality Tools

Our project uses several code quality tools, each with a specific purpose:

| Tool             | Purpose               | Configuration     |
| ---------------- | --------------------- | ----------------- |
| PHPStan/Larastan | Static analysis       | phpstan.neon.dist |
| Laravel Pint     | Code style formatting | pint.json         |
| Rector           | Automated refactoring | rector.php        |
| PHPInsights      | Code quality metrics  | phpinsights.php   |
| Infection        | Mutation testing      | infection.json5   |

All tools are configured with standardized settings defined in `config/code-quality.php`.

## 3. Local Development Workflow

### 3.1. Before Writing Code

1. Ensure your local environment is up-to-date:

   ```bash
   git pull
   composer install
   ```

2. Install Git hooks for pre-commit checks:
   ```bash
   composer git:hooks
   ```

### 3.2. While Writing Code

1. Use quick analysis to check your code frequently:

   ```bash
   composer analyze:quick
   ```

2. Format your code as you go:

   ```bash
   composer format
   ```

3. Check only changed files:
   ```bash
   composer analyze:changed
   ```

### 3.3. Before Committing

1. Run pre-commit checks (or let the Git hook do it):

   ```bash
   composer lint
   composer analyze:staged
   ```

2. Fix any issues:
   ```bash
   composer fix:all
   ```

### 3.4. Before Creating a Pull Request

1. Run comprehensive quality checks:

   ```bash
   composer quality:check
   ```

2. Generate a quality report:

   ```bash
   composer quality:report
   ```

3. Check if you meet quality thresholds:
   ```bash
   composer quality:gate
   ```

## 4. Understanding Quality Metrics

### 4.1. PHPStan Levels

PHPStan has 10 levels of strictness:

- Level 0: Basic checks
- Level 5: Intermediate checks (our minimum)
- Level 10: Strictest checks (our goal)

### 4.2. PHPInsights Scores

PHPInsights provides scores in four categories:

- Quality: Overall code quality
- Complexity: Code complexity metrics
- Architecture: Code architecture and structure
- Style: Code style and formatting

Our minimum threshold for all categories is 85%.

### 4.3. Infection MSI

Mutation Score Indicator (MSI) measures test effectiveness:

- Higher is better (our minimum is 85%)
- Indicates how well tests detect code changes

## 5. Fixing Common Issues

### 5.1. PHPStan Errors

| Error                                          | Solution                                        |
| ---------------------------------------------- | ----------------------------------------------- |
| Parameter #1 $x of function expects Y, Z given | Add type casting or fix the type                |
| Method X not found in class Y                  | Check for typos or implement the method         |
| Property X not found in class Y                | Define the property or use @property annotation |

### 5.2. Code Style Issues

| Issue              | Solution                              |
| ------------------ | ------------------------------------- |
| Indentation        | Run `composer format`                 |
| Import ordering    | Run `composer format`                 |
| Naming conventions | Follow PSR-12 and Laravel conventions |

### 5.3. Automated Fixes

Many issues can be fixed automatically:

```bash
composer fix:all
```

## 6. Progressive Quality Improvement

We use a phased approach to gradually improve code quality:

| Phase | PHPStan Level | Quality Thresholds | MSI |
| ----- | ------------- | ------------------ | --- |
| 1     | 5             | 75%                | 75% |
| 2     | 7             | 80%                | 80% |
| 3     | 10            | 85%                | 85% |

To check the current phase:

```bash
composer quality:phase
```

To advance to the next phase:

```bash
composer quality:phase:next
```

## 7. Managing PHPStan Baseline

The baseline contains ignored errors that we aim to fix over time:

1. Review the baseline:

   ```bash
   composer analyze:baseline-review
   ```

2. Update the baseline:
   ```bash
   composer analyze:baseline-update
   ```

## 8. Custom Rules

We have custom rules for project-specific patterns:

### 8.1. PHPStan Rules

- `NoDirectQueryInControllers`: Prevents direct DB queries in controllers

### 8.2. Rector Rules

- `UseEnumInsteadOfConstantsRector`: Suggests using enums instead of constant classes

## 9. CI/CD Integration

Our CI pipeline runs quality checks on every pull request:

1. Code style checks
2. Static analysis
3. Quality metrics
4. Test coverage
5. Mutation testing

A quality report is automatically added as a comment to each PR.

## 10. Troubleshooting

### 10.1. Memory Issues

If you encounter memory errors:

```bash
php -d memory_limit=-1 ./vendor/bin/phpstan analyse
```

### 10.2. Cache Issues

Clear caches if you get unexpected results:

```bash
composer analyze:clear-cache
```

### 10.3. Getting Help

If you're stuck with quality issues:

1. Check this guide
2. Review tool documentation
3. Ask for help in the #code-quality Slack channel
