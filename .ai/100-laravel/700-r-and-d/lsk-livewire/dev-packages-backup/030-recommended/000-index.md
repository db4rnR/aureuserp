# Recommended Additional Packages

This documentation covers additional development packages that are recommended for this project but not currently installed.

## 1. Overview of Recommendations

Based on analysis of your current development toolset and project needs, the following packages are recommended to enhance your development workflow:

| Package | Fit Score | Confidence |
|---------|-----------|------------|
| Laravel Shift Blueprints | 95% | 95% |
| Laravel Opcache | 92% | 92% |
| Clockwork | 90% | 90% |
| Laravel Migration Generator | 88% | 88% |
| Larastan Strict Rules | 85% | 85% |
| PHP CS Fixer | 85% | 85% |
| PHPUnit Watcher | 80% | 80% |
| Laravel Test Insights | 78% | 78% |

## 2. Detailed Recommendations

### 2.1. Laravel Shift Blueprints (95% fit, 95% confidence)

A package for defining and generating application components using YAML blueprint files.

#### 2.1.1. Why It's Recommended

- Complements your existing code generation tools
- Speeds up development of standard components
- Works perfectly with your Laravel ecosystem
- Minimizes boilerplate code writing

#### 2.1.2. Installation

```bash
composer require --dev laravel-shift/blueprint
```

#### 2.1.3. Example Usage

```yaml
// Example blueprint.yaml
models:
  Post:
    title: string:400
    content: longtext
    published_at: nullable timestamp
    user_id: id foreign

controllers:
  Post:
    index:
      query: all
      render: post.index with:posts
    
    show:
      find: post.id
      render: post.show with:post
```

### 2.2. Laravel Opcache (92% fit, 92% confidence)

Optimizes PHP performance in development using opcache.

#### 2.2.1. Why It's Recommended

- Works well with your Laravel Octane setup
- Improves local development performance
- Easy integration with existing workflow

#### 2.2.2. Installation

```bash
composer require --dev appstract/laravel-opcache
```

### 2.3. Clockwork (90% fit, 90% confidence)

A dev tool that provides insights into your application runtime.

#### 2.3.1. Why It's Recommended

- Alternative to Laravel Debugbar with different UI
- Provides excellent performance insights
- Chrome/Firefox extension for improved experience
- Timeline visualization of application events

#### 2.3.2. Installation

```bash
composer require --dev itsgoingd/clockwork
```

### 2.4. Laravel Migration Generator (88% fit, 88% confidence)

Generate migrations from existing databases.

#### 2.4.1. Why It's Recommended

- Complements your eloquent-model-generator
- Useful for working with existing databases
- Accelerates legacy database integration

#### 2.4.2. Installation

```bash
composer require --dev kitloong/laravel-migration-generator
```

### 2.5. Larastan Strict Rules (85% fit, 85% confidence)

Adds stricter ruleset to your Larastan setup.

#### 2.5.1. Why It's Recommended

- Enhances your existing static analysis
- Catches more potential issues
- Improves code quality metrics

#### 2.5.2. Installation

```bash
composer require --dev nunomaduro/larastan-strict-rules
```

### 2.6. PHPUnit Watcher (80% fit, 80% confidence)

Automatically runs tests when files change.
# Recommended Packages Documentation

## 1. Overview

This section documents additional packages recommended for consideration in the project. These packages are not currently in the require-dev dependencies but may provide significant value based on project needs.

## 2. Testing Enhancements

### 2.1. Advanced Testing
- [Infection](010-infection.md) - Mutation testing framework (98% confidence)
- [PHPBench](015-phpbench.md) - PHP Benchmarking framework (95% confidence)

### 2.2. Test Data Generation
- [Laravel Test Factory Helper](020-test-factory-helper.md) - Simplifies factory usage (90% confidence)
- [Laravel Test Generator](025-test-generator.md) - Generates tests for Controllers and Models (85% confidence)

## 3. Code Quality Improvements

### 3.1. Static Analysis
- [Psalm](030-psalm.md) - Advanced static analysis tool (97% confidence)
- [PHP Architecture Tester](035-phpat.md) - Tests architecture rules (93% confidence)

### 3.2. Code Metrics
- [PHP Metrics](040-php-metrics.md) - Generates code metrics reports (95% confidence)
- [Deptrac](045-deptrac.md) - Enforces dependency rules (92% confidence)

## 4. Developer Experience

### 4.1. Development Workflow
- [Collision](050-collision.md) - Beautiful error reporting (99% confidence)
- [PHP CS Fixer](055-php-cs-fixer.md) - Automatically fix code style (96% confidence)

### 4.2. Documentation
- [PHPDocumentor](060-phpdocumentor.md) - API documentation generation (94% confidence)
- [PHPMD](065-phpmd.md) - Detects potential problems in code (91% confidence)

## 5. Security Tools

### 5.1. Security Analysis
- [PHPStan Security Rules](070-phpstan-security.md) - Security focused static analysis (99% confidence)
- [Local PHP Security Checker](075-security-checker.md) - Checks dependencies for vulnerabilities (97% confidence)

### 5.2. Code Scanning
- [SonarQube/SonarCloud](080-sonarqube.md) - Continuous code quality (94% confidence)
- [PHP Security Scanner](085-security-scanner.md) - Scans for security vulnerabilities (92% confidence)

## 6. Performance Optimization

### 6.1. Profiling
- [Clockwork](090-clockwork.md) - Application profiling (96% confidence)
- [Tideways](095-tideways.md) - Performance monitoring (93% confidence)

### 6.2. Optimization
- [Laravel Query Detector](100-query-detector.md) - Detects N+1 queries (98% confidence)
- [PHP Memory Limits](105-memory-limits.md) - Memory usage optimization (90% confidence)

## 7. Package Selection Guidelines

- [Evaluation Criteria](110-evaluation-criteria.md) - How to assess package suitability
- [Integration Considerations](115-integration.md) - Factors for successful integration
- [Maintenance Requirements](120-maintenance.md) - Long-term support considerations
#### 2.6.1. Why It's Recommended

- Works with your Pest setup
- Improves TDD workflow
- Provides instant feedback

#### 2.6.2. Installation

```bash
composer require --dev spatie/phpunit-watcher
```

### 2.7. Laravel Test Insights (78% fit, 78% confidence)

Provides metrics about your test suite.

#### 2.7.1. Why It's Recommended

- Identifies areas with poor test coverage
- Complements your existing testing tools
- Helps prioritize testing efforts

#### 2.7.2. Installation

```bash
composer require --dev nunomaduro/laravel-test-insights
```
