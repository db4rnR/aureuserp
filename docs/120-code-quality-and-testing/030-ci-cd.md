# 3. CI/CD and Pre-commit Hooks

This document provides comprehensive instructions for setting up and using GitHub Actions and pre-commit hooks in the AureusERP project.

## 3.1. Introduction to CI/CD

Continuous Integration and Continuous Deployment (CI/CD) is a software development practice that involves automatically integrating code changes from multiple contributors into a shared repository, and then automatically building, testing, and deploying the application.

Benefits of CI/CD:
- Early detection of integration issues
- Automated testing and validation
- Consistent deployment process
- Faster feedback loop
- Reduced manual errors

## 3.2. GitHub Actions

GitHub Actions is a CI/CD platform that allows you to automate your build, test, and deployment pipeline. It's integrated directly into your GitHub repository.

### 3.2.1. Setting Up GitHub Actions

1. Create a `.github/workflows` directory in your repository:

```bash
mkdir -p .github/workflows
```

2. Create workflow files for different purposes:

#### 3.2.1.1. Code Quality Workflow

Create a file named `.github/workflows/code-quality.yml`:

```yaml
name: Code Quality

on:
  push:
    branches: [ 010-ddl, develop ]
  pull_request:
    branches: [ 010-ddl, develop ]

jobs:
  code-quality:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
          extensions: dom, curl, libxml, mbstring, zip, pcntl, pdo, sqlite, pdo_sqlite
          coverage: xdebug
          
      - name: Install Dependencies
        run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist
        
      - name: Check Code Style
        run: composer pint:test
        
      - name: Static Analysis
        run: composer phpstan
        
      - name: Rector Dry Run
        run: composer rector:dry-run
        
      - name: PHP Insights
        run: composer insights -- --min-quality=90 --min-complexity=90 --min-architecture=90 --min-style=90 --no-interaction
```

#### 3.2.1.2. Testing Workflow

Create a file named `.github/workflows/testing.yml`:

```yaml
name: Testing

on:
  push:
    branches: [ 010-ddl, develop ]
  pull_request:
    branches: [ 010-ddl, develop ]

jobs:
  testing:
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: password
          MYSQL_DATABASE: testing
        ports:
          - 3306:3306
        options: --health-cmd="mysqladmin ping" --health-interval=10s --health-timeout=5s --health-retries=3
    
    steps:
      - uses: actions/checkout@v4
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
          extensions: dom, curl, libxml, mbstring, zip, pcntl, pdo, sqlite, pdo_sqlite, mysql, pdo_mysql
          coverage: xdebug
          
      - name: Copy .env
        run: php -r "file_exists('.env') || copy('.env.example', '.env');"
        
      - name: Install Dependencies
        run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist
        
      - name: Generate key
        run: php artisan key:generate
        
      - name: Directory Permissions
        run: chmod -R 777 storage bootstrap/cache
        
      - name: Configure Database
        env:
          DB_CONNECTION: mysql
          DB_HOST: 127.0.0.1
          DB_PORT: 3306
          DB_DATABASE: testing
          DB_USERNAME: root
          DB_PASSWORD: password
        run: |
          php artisan config:clear
          php artisan migrate --seed
        
      - name: Run Tests
        env:
          DB_CONNECTION: mysql
          DB_HOST: 127.0.0.1
          DB_PORT: 3306
          DB_DATABASE: testing
          DB_USERNAME: root
          DB_PASSWORD: password
        run: composer test
        
      - name: Upload Coverage Reports
        uses: codecov/codecov-action@v3
        with:
          token: ${{ secrets.CODECOV_TOKEN }}
          files: ./reports/coverage/clover.xml
          fail_ci_if_error: true
```

#### 3.2.1.3. Deployment Workflow

Create a file named `.github/workflows/deploy.yml`:

```yaml
name: Deploy

on:
  push:
    branches: [ 010-ddl ]
    
jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
          extensions: dom, curl, libxml, mbstring, zip, pcntl, pdo, sqlite, pdo_sqlite
          
      - name: Install Dependencies
        run: composer install --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist --optimize-autoloader
        
      - name: Setup Node.js
        uses: actions/setup-node@v4
        with:
          node-version: '20'
          
      - name: Install NPM Dependencies
        run: npm ci
        
      - name: Build Assets
        run: npm run build
        
      - name: Deploy to Production
        uses: deployphp/action@v1
        with:
          private-key: ${{ secrets.DEPLOY_KEY }}
          dep: deploy production
```

### 3.2.2. Configuring GitHub Secrets

For secure deployment, you need to set up GitHub Secrets:

1. Go to your repository on GitHub
2. Click on "Settings" > "Secrets and variables" > "Actions"
3. Click on "New repository secret"
4. Add the following secrets:
   - `DEPLOY_KEY`: Your SSH private key for deployment
   - `CODECOV_TOKEN`: Your Codecov token for coverage reports

### 3.2.3. Viewing Workflow Results

1. Go to your repository on GitHub
2. Click on the "Actions" tab
3. Select a workflow to view its details
4. Click on a specific run to view the logs and results

## 3.3. Pre-commit Hooks

Pre-commit hooks are scripts that run before each commit is made, ensuring that the code meets certain quality standards before it's committed to the repository.

### 3.3.1. Setting Up Pre-commit Hooks

1. Install the pre-commit package:

```bash
pip install pre-commit
```

2. Create a `.pre-commit-config.yaml` file in the root of your repository:

```yaml
repos:
  - repo: https://github.com/pre-commit/pre-commit-hooks
    rev: v4.5.0
    hooks:
      - id: trailing-whitespace
      - id: end-of-file-fixer
      - id: check-yaml
      - id: check-added-large-files
      
  - repo: local
    hooks:
      - id: pint
        name: Laravel Pint
        entry: vendor/bin/pint
        language: system
        types: [php]
        
      - id: phpstan
        name: PHPStan
        entry: vendor/bin/phpstan analyse
        language: system
        types: [php]
        
      - id: rector
        name: Rector
        entry: vendor/bin/rector process
        language: system
        types: [php]
        
      - id: pest
        name: Pest
        entry: vendor/bin/pest
        language: system
        types: [php]
        pass_filenames: false
```

3. Install the pre-commit hooks:

```bash
pre-commit install
```

### 3.3.2. Using Git Hooks with Composer

You can also set up Git hooks using Composer by adding the following to your `composer.json` file:

```json
{
    "scripts": {
        "post-install-cmd": [
            "[ -d .git ] && git config core.hooksPath .git/hooks || true",
            "[ -d .git ] && cp -f .git-hooks/* .git/hooks/ || true",
            "[ -d .git ] && chmod +x .git/hooks/* || true"
        ],
        "post-update-cmd": [
            "[ -d .git ] && git config core.hooksPath .git/hooks || true",
            "[ -d .git ] && cp -f .git-hooks/* .git/hooks/ || true",
            "[ -d .git ] && chmod +x .git/hooks/* || true"
        ]
    }
}
```

Then create a `.git-hooks` directory in the root of your repository:

```bash
mkdir -p .git-hooks
```

Create a pre-commit hook file at `.git-hooks/pre-commit`:

```bash
#!/bin/bash

echo "Running pre-commit hooks..."

# Run Laravel Pint
echo "Running Laravel Pint..."
./vendor/bin/pint --test
if [ $? -ne 0 ]; then
    echo "Laravel Pint found issues. Please fix them before committing."
    exit 1
fi

# Run PHPStan
echo "Running PHPStan..."
./vendor/bin/phpstan analyse
if [ $? -ne 0 ]; then
    echo "PHPStan found issues. Please fix them before committing."
    exit 1
fi

# Run Rector
echo "Running Rector..."
./vendor/bin/rector process --dry-run
if [ $? -ne 0 ]; then
    echo "Rector found issues. Please fix them before committing."
    exit 1
fi

# Run Pest tests
echo "Running Pest tests..."
./vendor/bin/pest
if [ $? -ne 0 ]; then
    echo "Tests failed. Please fix them before committing."
    exit 1
fi

echo "All pre-commit hooks passed!"
exit 0
```

Make the pre-commit hook executable:

```bash
chmod +x .git-hooks/pre-commit
```

### 3.3.3. Bypassing Pre-commit Hooks

In some cases, you may need to bypass the pre-commit hooks. You can do this by using the `--no-verify` flag:

```bash
git commit -m "Your commit message" --no-verify
```

However, this should be used sparingly and only in exceptional circumstances.

## 3.4. Continuous Deployment

Continuous Deployment (CD) is the practice of automatically deploying code changes to production after they pass all tests and quality checks.

### 3.4.1. Setting Up Deployer

[Deployer](https://deployer.org/) is a deployment tool for PHP applications. It allows you to deploy your application to multiple servers with zero downtime.

1. Install Deployer:

```bash
composer require deployer/deployer --dev
```

2. Initialize Deployer:

```bash
vendor/bin/dep init
```

3. Configure your `deploy.php` file:

```php
<?php

namespace Deployer;

require 'recipe/laravel.php';

// Config
set('repository', 'git@github.com:your-username/your-repository.git');
set('git_tty', true);
set('keep_releases', 5);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', [
    'storage',
]);

// Writable dirs by web server
add('writable_dirs', [
    'bootstrap/cache',
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
]);

// Hosts
host('production')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '/var/www/production');

host('staging')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '/var/www/staging');

// Tasks
task('build', function () {
    run('cd {{release_path}} && npm ci');
    run('cd {{release_path}} && npm run build');
});

// Hooks
after('deploy:failed', 'deploy:unlock');
after('deploy:vendors', 'build');
```

4. Deploy your application:

```bash
vendor/bin/dep deploy production
```

### 3.4.2. Zero Downtime Deployment

Deployer supports zero downtime deployment by default. It creates a new release directory, builds the application, and then switches the symlink to the new release.

### 3.4.3. Rollback

If something goes wrong during deployment, you can roll back to the previous release:

```bash
vendor/bin/dep rollback production
```

## 3.5. Monitoring and Notifications

### 3.5.1. Setting Up Slack Notifications

You can set up Slack notifications for your GitHub Actions workflows:

1. Create a Slack app and get a webhook URL
2. Add the webhook URL to your GitHub Secrets as `SLACK_WEBHOOK`
3. Update your workflow files to include Slack notifications:

```yaml
- name: Slack Notification
  uses: 8398a7/action-slack@v3
  with:
    status: ${{ job.status }}
    fields: repo,message,commit,author,action,eventName,ref,workflow
  env:
    SLACK_WEBHOOK_URL: ${{ secrets.SLACK_WEBHOOK }}
  if: always()
```

### 3.5.2. Setting Up Email Notifications

GitHub can send email notifications for workflow failures:

1. Go to your GitHub profile
2. Click on "Settings" > "Notifications"
3. Configure your email notification preferences

## 3.6. Best Practices

1. **Run tests locally before pushing**: Always run tests locally before pushing to the repository.
2. **Keep workflows focused**: Each workflow should have a specific purpose.
3. **Use caching**: Cache dependencies to speed up workflows.
4. **Secure sensitive information**: Use GitHub Secrets for sensitive information.
5. **Monitor workflow performance**: Regularly check workflow execution times and optimize as needed.
6. **Document workflow requirements**: Document any special requirements for workflows.
7. **Test workflows locally**: Test workflows locally using [act](https://github.com/nektos/act) before pushing.
8. **Use matrix builds**: Use matrix builds to test against multiple PHP versions and databases.
9. **Set up branch protection rules**: Require status checks to pass before merging.
10. **Automate dependency updates**: Use Dependabot to automate dependency updates.
