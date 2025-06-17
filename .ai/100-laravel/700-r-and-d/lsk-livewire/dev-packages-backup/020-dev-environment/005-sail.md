# Laravel Sail

## 1. Overview

Laravel Sail is a light-weight command-line interface for interacting with Laravel's default Docker development environment, providing a great starting point for building a Laravel application using PHP, MySQL, and Redis without requiring prior Docker experience.

### 1.1. Package Information

- **Package Name**: laravel/sail
- **Version**: ^1.41
- **GitHub**: [https://github.com/laravel/sail](https://github.com/laravel/sail)
- **Documentation**: [https://laravel.com/docs/sail](https://laravel.com/docs/sail)

## 2. Key Features

- Simple Docker configuration
- Easy-to-use CLI
- Pre-configured services (MySQL, Redis, etc.)
- No Docker knowledge required
- Support for multiple PHP versions
- Integration with Laravel ecosystem

## 3. Usage Examples

### 3.1. Basic Commands

```sh
##/ Start all containers
./vendor/bin/sail up

## Start in detached mode
./vendor/bin/sail up -d

## Stop containers
./vendor/bin/sail down

## Run artisan commands
./vendor/bin/sail artisan migrate

## Run tests
./vendor/bin/sail test
```

### 3.2. Shell Access

```sh
## Access the application shell
./vendor/bin/sail shell

## Access specific service shell
./vendor/bin/sail shell mysql

## Open a Tinker session
./vendor/bin/sail tinker
```

### 3.3. PHP Commands

```sh
## Run PHP commands
./vendor/bin/sail php --version

## Run Composer commands
./vendor/bin/sail composer require spatie/laravel-permission
```

## 4. Configuration

Sail's configuration is managed through the `docker-compose.yml` file and the `sail` script:

```yaml
# docker-compose.yml
services:
    laravel.test:
        build:
            context: ./vendor/laravel/sail/runtimes/8.2
            dockerfile: Dockerfile
            args:
                WWWGROUP: '${WWWGROUP}'
        image: sail-8.2/app
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
    # Other services...
```

## 5. Customizing Sail

### 5.1. Adding Services

You can add additional services to your Sail environment:

Publish the docker-compose.yml file
```sh
## Publish the docker-compose.yml file
php artisan sail:publish
````
Then edit docker-compose.yml to add services

### 5.2. Creating an Alias

Create a Bash alias for easier usage:

```bash
# Add to ~/.bashrc or ~/.zshrc
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

### 5.3. Custom PHP Extensions

Customize the Dockerfile to add PHP extensions:

```dockerfile
FROM ubuntu:22.04

# Install packages and PHP extensions
RUN apt-get update \
    && apt-get install -y php8.2-cli php8.2-gd php8.2-curl \
    php8.2-imap php8.2-mysql php8.2-mbstring \
    php8.2-xml php8.2-zip php8.2-bcmath php8.2-soap \
    php8.2-intl php8.2-readline \
    php8.2-ldap \
    php8.2-msgpack php8.2-igbinary php8.2-redis php8.2-swoole \
    php8.2-memcached php8.2-pcov php8.2-xdebug \
    # Add your custom extensions here
    php8.2-imagick \
    && apt-get clean
```

## 6. Integration with Other Tools

### 6.1. Using Xdebug

Configure Xdebug for debugging:

In .env
```env
SAIL_XDEBUG_MODE=develop,debug
SAIL_XDEBUG_CONFIG=client_host=host.docker.internal
```

Then restart Sail
```sh
sail down
sail up -d
```

### 6.2. Running Queue Workers

Set up queue workers within Sail:

```sh
## Start queue worker
./vendor/bin/sail artisan queue:work

## Or using supervisor (requires customization)
```
