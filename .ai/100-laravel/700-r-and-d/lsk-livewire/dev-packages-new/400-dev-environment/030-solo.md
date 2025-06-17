# Solo

## 1. Overview

Solo is a lightweight development environment manager for PHP applications. It provides a simple, fast, and customizable way to manage your development environment without the complexity of Docker or virtual machines.

### 1.1. Package Information

- **Package Name**: soloterm/solo
- **Version**: ^0.5.0
- **GitHub**: [https://github.com/soloterm/solo](https://github.com/soloterm/solo)
- **Documentation**: [https://github.com/soloterm/solo#readme](https://github.com/soloterm/solo#readme)

## 2. Key Features

- Lightweight development environment
- Fast startup time
- Low resource usage
- Simple configuration
- Support for multiple PHP versions
- Built-in web server
- Database management
- Redis support
- Queue management
- Scheduler support
- Environment variable management
- Integration with Laravel
- Support for PHP 8.4

## 3. Installation

```bash
composer require --dev soloterm/solo
```

## 4. Configuration

### 4.1. Basic Configuration

Create a `solo.json` file in your project root:

```json
{
    "name": "my-app",
    "php": "8.4",
    "web": {
        "host": "my-app.test",
        "port": 8000,
        "public": "public"
    },
    "database": {
        "type": "mysql",
        "name": "my_app",
        "user": "root",
        "password": ""
    },
    "redis": {
        "enabled": true,
        "port": 6379
    },
    "queue": {
        "enabled": true,
        "connection": "redis"
    },
    "scheduler": {
        "enabled": true
    }
}
```

### 4.2. Configuration Options

| Section | Option | Description | Default |
|---------|--------|-------------|---------|
| Root | `name` | The name of your application | Project directory name |
| Root | `php` | The PHP version to use | System default |
| `web` | `host` | The hostname to use | `localhost` |
| `web` | `port` | The port to use | `8000` |
| `web` | `public` | The public directory | `public` |
| `database` | `type` | The database type (mysql, pgsql, sqlite) | `mysql` |
| `database` | `name` | The database name | Project name |
| `database` | `user` | The database username | `root` |
| `database` | `password` | The database password | `""` |
| `redis` | `enabled` | Whether to enable Redis | `false` |
| `redis` | `port` | The Redis port | `6379` |
| `queue` | `enabled` | Whether to enable queue worker | `false` |
| `queue` | `connection` | The queue connection | `redis` |
| `scheduler` | `enabled` | Whether to enable scheduler | `false` |

### 4.3. Environment Variables

Set environment variables in the `env` section of your `solo.json`:

```json
"env": {
    "APP_URL": "http://my-app.test",
    "DB_HOST": "localhost",
    "DB_DATABASE": "my_app",
    "DB_USERNAME": "root",
    "DB_PASSWORD": ""
}
```

## 5. Usage

### 5.1. Starting Solo

Start the Solo environment:

```bash
./vendor/bin/solo up
```

### 5.2. Stopping Solo

Stop the Solo environment:

```bash
./vendor/bin/solo down
```

### 5.3. Restarting Solo

Restart the Solo environment:

```bash
./vendor/bin/solo restart
```

### 5.4. Checking Status

Check the status of the Solo environment:

```bash
./vendor/bin/solo status
```

### 5.5. Viewing Logs

View the Solo logs:

```bash
./vendor/bin/solo logs
```

### 5.6. Database Management

Create a database:

```bash
./vendor/bin/solo db:create
```

Drop a database:

```bash
./vendor/bin/solo db:drop
```

Access the database shell:

```bash
./vendor/bin/solo db:shell
```

### 5.7. Running Artisan Commands

Run Artisan commands:

```bash
./vendor/bin/solo artisan migrate
./vendor/bin/solo artisan db:seed
./vendor/bin/solo artisan make:controller UserController
```

## 6. Integration with Laravel 12 and PHP 8.4

Solo is fully compatible with Laravel 12 and PHP 8.4. It provides a lightweight alternative to Laravel Sail for local development.

### 6.1. Laravel Integration

For Laravel projects, Solo automatically:

1. Detects the Laravel environment
2. Sets up the correct document root
3. Configures environment variables
4. Supports Laravel's routing system
5. Manages database connections
6. Handles queue workers
7. Runs the scheduler

### 6.2. Laravel-Specific Commands

Solo provides Laravel-specific commands:

```bash
# Run migrations
./vendor/bin/solo artisan migrate

# Run seeders
./vendor/bin/solo artisan db:seed

# Clear cache
./vendor/bin/solo artisan cache:clear

# Generate key
./vendor/bin/solo artisan key:generate
```

## 7. Advanced Usage

### 7.1. Custom PHP Configuration

Create a custom `php.ini` file in your project root to override PHP settings.

### 7.2. Multiple Projects

Run multiple Solo projects simultaneously by using different ports:

```json
{
    "name": "project1",
    "web": {
        "port": 8001
    },
    "redis": {
        "port": 6380
    }
}
```

```json
{
    "name": "project2",
    "web": {
        "port": 8002
    },
    "redis": {
        "port": 6381
    }
}
```

### 7.3. Custom Commands

Define custom commands in your `solo.json`:

```json
"commands": {
    "test": "php artisan test",
    "lint": "php artisan pint",
    "analyze": "php artisan phpstan"
}
```

Run custom commands:

```bash
./vendor/bin/solo run test
./vendor/bin/solo run lint
./vendor/bin/solo run analyze
```

## 8. Composer Scripts

Add Solo commands to your `composer.json`:

```json
"scripts": {
    "dev": [
        "./vendor/bin/solo up"
    ],
    "dev:down": [
        "./vendor/bin/solo down"
    ],
    "dev:restart": [
        "./vendor/bin/solo restart"
    ],
    "db:reset": [
        "./vendor/bin/solo db:drop",
        "./vendor/bin/solo db:create",
        "./vendor/bin/solo artisan migrate --seed"
    ]
}
```

Usage:

```bash
composer dev
composer dev:down
composer dev:restart
composer db:reset
```

## 9. Best Practices

1. **Version Control**: Add `solo.json` to version control
2. **Exclude Generated Files**: Add Solo's generated files to `.gitignore`:
   ```
   .solo/
   ```
3. **Use Environment Variables**: Configure environment-specific settings using environment variables
4. **Custom Commands**: Define custom commands for common tasks
5. **Database Management**: Use Solo's database commands for easy database management

## 10. Troubleshooting

### 10.1. Port Conflicts

If you encounter port conflicts:

1. Change the port in your `solo.json`:
   ```json
   "web": {
       "port": 8080
   },
   "redis": {
       "port": 6380
   }
   ```

2. Restart Solo:
   ```bash
   ./vendor/bin/solo restart
   ```

### 10.2. Database Connection Issues

If you encounter database connection issues:

1. Check your database configuration in `solo.json`
2. Ensure the database server is running
3. Verify the database credentials
4. Try creating the database manually:
   ```bash
   ./vendor/bin/solo db:create
   ```

### 10.3. Performance Issues

If you encounter performance issues:

1. Check your PHP configuration
2. Ensure you have enough memory allocated to PHP
3. Use opcache for better performance
4. Disable features you don't need (Redis, queue, scheduler)
