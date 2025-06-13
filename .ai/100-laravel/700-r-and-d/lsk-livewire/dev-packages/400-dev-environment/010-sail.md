# Laravel Sail

## 1. Overview

Laravel Sail is a light-weight command-line interface for interacting with Laravel's default Docker development environment. Sail provides a great starting point for building a Laravel application using PHP, MySQL, and Redis without requiring prior Docker experience.

### 1.1. Package Information

- **Package Name**: laravel/sail
- **Version**: ^1.41
- **GitHub**: [https://github.com/laravel/sail](https://github.com/laravel/sail)
- **Documentation**: [https://laravel.com/docs/10.x/sail](https://laravel.com/docs/10.x/sail)

## 2. Key Features

- Simple Docker-based development environment
- Zero Docker knowledge required
- Pre-configured services (PHP, MySQL, Redis, etc.)
- Easy service customization
- Convenient CLI commands
- Integration with Laravel ecosystem
- Support for multiple PHP versions
- Customizable Docker configuration
- Support for additional services
- Seamless testing integration

## 3. Installation

Laravel Sail is included by default in new Laravel applications. If you need to add it to an existing application:

```bash
composer require --dev laravel/sail
php artisan sail:install
```

This will create a `docker-compose.yml` file in your project root.

## 4. Configuration

### 4.1. Docker Compose Configuration

The main configuration file is `docker-compose.yml` in your project root:

```yaml
version: '3'
services:
    laravel.test:
        build:
            context: ./vendor/laravel/sail/runtimes/8.4
            dockerfile: Dockerfile
            args:
                WWWGROUP: '${WWWGROUP}'
        image: sail-8.4/app
        extra_hosts:
            - 'host.docker.internal:host-gateway'
        ports:
            - '${APP_PORT:-80}:80'
            - '${VITE_PORT:-5173}:${VITE_PORT:-5173}'
        environment:
            WWWUSER: '${WWWUSER}'
            LARAVEL_SAIL: 1
            XDEBUG_MODE: '${SAIL_XDEBUG_MODE:-off}'
            XDEBUG_CONFIG: '${SAIL_XDEBUG_CONFIG:-client_host=host.docker.internal}'
            IGNITION_LOCAL_SITES_PATH: '${PWD}'
        volumes:
            - '.:/var/www/html'
        networks:
            - sail
        depends_on:
            - mysql
            - redis
    mysql:
        image: 'mysql/mysql-server:8.0'
        ports:
            - '${FORWARD_DB_PORT:-3306}:3306'
        environment:
            MYSQL_ROOT_PASSWORD: '${DB_PASSWORD}'
            MYSQL_ROOT_HOST: '%'
            MYSQL_DATABASE: '${DB_DATABASE}'
            MYSQL_USER: '${DB_USERNAME}'
            MYSQL_PASSWORD: '${DB_PASSWORD}'
            MYSQL_ALLOW_EMPTY_PASSWORD: 1
        volumes:
            - 'sail-mysql:/var/lib/mysql'
            - './vendor/laravel/sail/database/mysql/create-testing-database.sh:/docker-entrypoint-initdb.d/10-create-testing-database.sh'
        networks:
            - sail
        healthcheck:
            test: ['CMD', 'mysqladmin', 'ping', '-p${DB_PASSWORD}']
            retries: 3
            timeout: 5s
    redis:
        image: 'redis:alpine'
        ports:
            - '${FORWARD_REDIS_PORT:-6379}:6379'
        volumes:
            - 'sail-redis:/data'
        networks:
            - sail
        healthcheck:
            test: ['CMD', 'redis-cli', 'ping']
            retries: 3
            timeout: 5s
networks:
    sail:
        driver: bridge
volumes:
    sail-mysql:
        driver: local
    sail-redis:
        driver: local
```

### 4.2. Environment Configuration

Configure Sail in your `.env` file:

```
# Sail configuration
APP_PORT=80
FORWARD_DB_PORT=3306
FORWARD_REDIS_PORT=6379
SAIL_XDEBUG_MODE=develop,debug
SAIL_XDEBUG_CONFIG=client_host=host.docker.internal
```

### 4.3. PHP Version

Sail supports multiple PHP versions. To change the PHP version, modify the `build.context` in `docker-compose.yml`:

```yaml
build:
    context: ./vendor/laravel/sail/runtimes/8.4  # For PHP 8.4
```

Available versions: 7.4, 8.0, 8.1, 8.2, 8.3, 8.4

### 4.4. Additional Services

Add additional services to your `docker-compose.yml`:

```yaml
# Add Mailhog
mailhog:
    image: 'mailhog/mailhog:latest'
    ports:
        - '${FORWARD_MAILHOG_PORT:-1025}:1025'
        - '${FORWARD_MAILHOG_DASHBOARD_PORT:-8025}:8025'
    networks:
        - sail

# Add Minio (S3)
minio:
    image: 'minio/minio:latest'
    ports:
        - '${FORWARD_MINIO_PORT:-9000}:9000'
        - '${FORWARD_MINIO_CONSOLE_PORT:-8900}:8900'
    environment:
        MINIO_ROOT_USER: 'sail'
        MINIO_ROOT_PASSWORD: 'password'
    volumes:
        - 'sail-minio:/data/minio'
    networks:
        - sail
    command: minio server /data/minio --console-address ":8900"
    healthcheck:
        test: ['CMD', 'curl', '-f', 'http://localhost:9000/minio/health/live']
        retries: 3
        timeout: 5s
```

## 5. Usage

### 5.1. Starting Sail

Start the Sail environment:

```bash
./vendor/bin/sail up
```

Start in detached mode:

```bash
./vendor/bin/sail up -d
```

### 5.2. Stopping Sail

Stop the Sail environment:

```bash
./vendor/bin/sail down
```

### 5.3. Executing Commands

Execute commands in the Sail environment:

```bash
# Run Artisan commands
./vendor/bin/sail artisan migrate

# Run Composer commands
./vendor/bin/sail composer require spatie/laravel-permission

# Run NPM commands
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev

# Run tests
./vendor/bin/sail test
```

### 5.4. Shell Access

Access the shell in the Sail environment:

```bash
./vendor/bin/sail shell
```

### 5.5. MySQL Access

Access MySQL in the Sail environment:

```bash
./vendor/bin/sail mysql
```

### 5.6. Redis Access

Access Redis in the Sail environment:

```bash
./vendor/bin/sail redis
```

## 6. Integration with Laravel 12 and PHP 8.4

Laravel Sail is fully compatible with Laravel 12 and PHP 8.4. It includes:

- PHP 8.4 runtime
- Support for Laravel 12 features
- Compatibility with all Laravel 12 packages
- Support for Vite
- Support for Livewire
- Support for Inertia.js

## 7. Advanced Usage

### 7.1. Customizing the Dockerfile

Create a custom Dockerfile:

```bash
php artisan sail:publish
```

This will publish the Dockerfile to your project root, allowing you to customize it.

### 7.2. Sharing Your Site

Share your site using Expose:

```bash
./vendor/bin/sail share
```

### 7.3. Debugging with Xdebug

Enable Xdebug:

```
# .env
SAIL_XDEBUG_MODE=develop,debug
SAIL_XDEBUG_CONFIG=client_host=host.docker.internal
```

Restart Sail:

```bash
./vendor/bin/sail down
./vendor/bin/sail up -d
```

### 7.4. Using Sail in CI/CD

Use Sail in GitHub Actions:

```yaml
name: Tests

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]

jobs:
  tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
      - name: Install Dependencies
        run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress
      - name: Start Sail
        run: ./vendor/bin/sail up -d
      - name: Run Tests
        run: ./vendor/bin/sail test
```

## 8. Best Practices

1. **Use Sail Alias**: Create a Bash alias for Sail:
   ```bash
   alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
   ```

2. **Version Control**: Add Docker-related files to version control:
   ```
   docker-compose.yml
   docker/
   ```

3. **Exclude Volumes**: Add Docker volumes to `.gitignore`:
   ```
   .sail-mysql/
   .sail-redis/
   ```

4. **Environment-Specific Config**: Use environment-specific Docker Compose files:
   ```
   docker-compose.yml
   docker-compose.override.yml
   ```

5. **Resource Limits**: Set resource limits in Docker Compose:
   ```yaml
   services:
     laravel.test:
       deploy:
         resources:
           limits:
             cpus: '0.50'
             memory: 512M
   ```

## 9. Troubleshooting

### 9.1. Port Conflicts

If you encounter port conflicts:

1. Change the ports in your `.env` file:
   ```
   APP_PORT=8000
   FORWARD_DB_PORT=33060
   FORWARD_REDIS_PORT=63790
   ```

2. Restart Sail:
   ```bash
   ./vendor/bin/sail down
   ./vendor/bin/sail up -d
   ```

### 9.2. Permission Issues

If you encounter permission issues:

1. Check the `WWWUSER` and `WWWGROUP` environment variables
2. Run Sail with the correct user:
   ```bash
   WWWUSER=$(id -u) WWWGROUP=$(id -g) ./vendor/bin/sail up -d
   ```

### 9.3. Performance Issues

If you encounter performance issues:

1. Use Docker Desktop's resource settings to allocate more resources
2. Use Docker volumes for better performance:
   ```yaml
   volumes:
     - .:/var/www/html:cached
   ```

3. Consider using NFS volumes on macOS
