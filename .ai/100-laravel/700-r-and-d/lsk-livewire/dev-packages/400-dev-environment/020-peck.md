# Peck PHP

## 1. Overview

Peck PHP is a lightweight development server for PHP applications. It provides a simple, fast, and customizable alternative to Laravel Sail or other Docker-based development environments, with a focus on speed and simplicity.

### 1.1. Package Information

- **Package Name**: peckphp/peck
- **Version**: ^0.1.3
- **GitHub**: [https://github.com/peckphp/peck](https://github.com/peckphp/peck)
- **Documentation**: [https://github.com/peckphp/peck#readme](https://github.com/peckphp/peck#readme)

## 2. Key Features

- Lightweight PHP development server
- Fast startup time
- Low resource usage
- Simple configuration
- Support for multiple PHP versions
- Built-in SSL support
- Custom domain support
- Automatic HTTPS
- Hot reloading
- Integration with Laravel
- Support for PHP 8.4

## 3. Installation

```bash
composer require --dev peckphp/peck
```

## 4. Configuration

### 4.1. Basic Configuration

Create a `peck.json` file in your project root:

```json
{
    "name": "my-app",
    "host": "my-app.test",
    "public": "public",
    "php": "8.4",
    "https": true,
    "env": {
        "APP_URL": "https://my-app.test",
        "DB_HOST": "localhost",
        "DB_DATABASE": "my_app",
        "DB_USERNAME": "root",
        "DB_PASSWORD": ""
    }
}
```

### 4.2. Configuration Options

| Option | Description | Default |
|--------|-------------|---------|
| `name` | The name of your application | Project directory name |
| `host` | The hostname to use | `localhost` |
| `public` | The public directory | `public` |
| `php` | The PHP version to use | System default |
| `https` | Whether to use HTTPS | `false` |
| `env` | Environment variables | `{}` |
| `port` | The port to use | `8000` |
| `ssl_port` | The SSL port to use | `8443` |
| `ssl_cert` | The SSL certificate path | Auto-generated |
| `ssl_key` | The SSL key path | Auto-generated |

### 4.3. Environment Variables

Set environment variables in the `env` section of your `peck.json`:

```json
"env": {
    "APP_URL": "https://my-app.test",
    "DB_HOST": "localhost",
    "DB_DATABASE": "my_app",
    "DB_USERNAME": "root",
    "DB_PASSWORD": ""
}
```

## 5. Usage

### 5.1. Starting the Server

Start the Peck server:

```bash
./vendor/bin/peck serve
```

### 5.2. Stopping the Server

Stop the Peck server:

```bash
./vendor/bin/peck stop
```

### 5.3. Restarting the Server

Restart the Peck server:

```bash
./vendor/bin/peck restart
```

### 5.4. Checking Server Status

Check the status of the Peck server:

```bash
./vendor/bin/peck status
```

### 5.5. Viewing Logs

View the Peck server logs:

```bash
./vendor/bin/peck logs
```

## 6. Integration with Laravel 12 and PHP 8.4

Peck PHP is fully compatible with Laravel 12 and PHP 8.4. It provides a lightweight alternative to Laravel Sail for local development.

### 6.1. Laravel Integration

For Laravel projects, Peck automatically:

1. Detects the Laravel environment
2. Sets up the correct document root
3. Configures environment variables
4. Supports Laravel's routing system

### 6.2. Artisan Commands

Run Artisan commands with Peck:

```bash
./vendor/bin/peck artisan migrate
./vendor/bin/peck artisan db:seed
./vendor/bin/peck artisan make:controller UserController
```

## 7. Advanced Usage

### 7.1. Custom Domains

Configure custom domains in your `peck.json`:

```json
{
    "name": "my-app",
    "host": "my-app.test",
    "public": "public",
    "php": "8.4",
    "https": true
}
```

Add the domain to your hosts file:

```
127.0.0.1 my-app.test
```

### 7.2. SSL Configuration

Configure SSL in your `peck.json`:

```json
{
    "name": "my-app",
    "host": "my-app.test",
    "public": "public",
    "php": "8.4",
    "https": true,
    "ssl_cert": "/path/to/cert.pem",
    "ssl_key": "/path/to/key.pem"
}
```

If you don't specify `ssl_cert` and `ssl_key`, Peck will generate self-signed certificates automatically.

### 7.3. Multiple Projects

Run multiple Peck projects simultaneously by using different ports:

```json
{
    "name": "project1",
    "host": "project1.test",
    "port": 8001,
    "ssl_port": 8444
}
```

```json
{
    "name": "project2",
    "host": "project2.test",
    "port": 8002,
    "ssl_port": 8445
}
```

### 7.4. Custom PHP Configuration

Create a custom `php.ini` file in your project root to override PHP settings.

## 8. Composer Scripts

Add Peck commands to your `composer.json`:

```json
"scripts": {
    "dev": [
        "./vendor/bin/peck serve"
    ],
    "dev:stop": [
        "./vendor/bin/peck stop"
    ],
    "dev:restart": [
        "./vendor/bin/peck restart"
    ]
}
```

Usage:

```bash
composer dev
composer dev:stop
composer dev:restart
```

## 9. Best Practices

1. **Version Control**: Add `peck.json` to version control
2. **Exclude Generated Files**: Add Peck's generated files to `.gitignore`:
   ```
   .peck/
   ```
3. **Use Environment Variables**: Configure environment-specific settings using environment variables
4. **Custom Domains**: Use custom domains for better local development experience
5. **SSL Development**: Use HTTPS locally to match production environment

## 10. Troubleshooting

### 10.1. Port Conflicts

If you encounter port conflicts:

1. Change the port in your `peck.json`:
   ```json
   {
       "port": 8080,
       "ssl_port": 8443
   }
   ```

2. Restart Peck:
   ```bash
   ./vendor/bin/peck restart
   ```

### 10.2. SSL Certificate Issues

If you encounter SSL certificate issues:

1. Trust the self-signed certificate in your browser
2. Use your own certificates by specifying `ssl_cert` and `ssl_key` in `peck.json`
3. Disable HTTPS by setting `https` to `false` in `peck.json`

### 10.3. Performance Issues

If you encounter performance issues:

1. Check your PHP configuration
2. Ensure you have enough memory allocated to PHP
3. Use opcache for better performance
