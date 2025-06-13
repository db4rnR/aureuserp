# Phase 5.0: Deployment Guide

**Version:** 1.0.1
**Date:** 2025-05-17
**Author:** AI Assistant
**Status:** Updated
**Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [Server Requirements](#server-requirements)
- [Deployment Environments](#deployment-environments)
  - [Local Development](#local-development)
  - [Staging Environment](#staging-environment)
  - [Production Environment](#production-environment)
- [Deployment Process](#deployment-process)
  - [Manual Deployment](#manual-deployment)
  - [Automated Deployment with CI/CD](#automated-deployment-with-cicd)
- [Environment Configuration](#environment-configuration)
- [Database Setup](#database-setup)
- [File Storage Configuration](#file-storage-configuration)
- [Caching and Queue Configuration](#caching-and-queue-configuration)
- [SSL Configuration](#ssl-configuration)
- [Monitoring and Logging](#monitoring-and-logging)
- [Backup Strategy](#backup-strategy)
- [Scaling Considerations](#scaling-considerations)
- [Troubleshooting](#troubleshooting)
</details>

## Overview

This guide provides comprehensive instructions for deploying the Enhanced Laravel Application (ELA) to various environments. It covers server requirements, deployment processes, configuration, and best practices for maintaining a robust production environment.

## Server Requirements

The Enhanced Laravel Application requires the following server components:

| Component | Minimum Requirement | Recommended |
|-----------|---------------------|-------------|
| PHP | 8.4.0 | 8.4.x (latest) |
| Database | PostgreSQL 16.0 | PostgreSQL 16.x (latest) |
| Web Server | Nginx 1.24.0 | Nginx 1.24.x (latest) |
| Runtime | FrankenPHP 1.0.0 | FrankenPHP 1.x (latest) |
| Memory | 2GB RAM | 4GB+ RAM |
| Storage | 20GB SSD | 40GB+ SSD |
| OS | Ubuntu 24.04 LTS | Ubuntu 24.04 LTS |

Additional PHP extensions required:
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- PostgreSQL PHP Extension
- Redis PHP Extension (for caching/queue)

## Deployment Environments

### Local Development

For local development, we recommend using Laravel Herd, which provides a pre-configured environment with all necessary components.

1. Install Laravel Herd from [https://herd.laravel.com/](https:/herd.laravel.com)
2. Clone the repository:
   ```bash
   git clone https://github.com/your-organization/enhanced-laravel-application.git
   cd enhanced-laravel-application
   ```
3. Install dependencies:
   ```bash
   composer install
   npm install
   ```
4. Set up environment variables:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
5. Configure the database in `.env`:
   ```
   DB_CONNECTION=sqlite
   DB_DATABASE=database/database.sqlite
   ```
6. Run migrations and seed the database:
   ```bash
   touch database/database.sqlite
   php artisan migrate --seed
   ```
7. Build frontend assets:
   ```bash
   npm run dev
   ```
8. Start the development server:
   ```bash
   php artisan serve
   ```

### Staging Environment

The staging environment should mirror the production environment as closely as possible to catch any deployment issues before they affect production.

Recommended setup:
- Virtual Private Server (VPS) with 2GB RAM
- Nginx + FrankenPHP
- PostgreSQL database
- Redis for caching and queues

### Production Environment

The production environment requires a robust, scalable setup with proper monitoring and backup solutions.

Recommended setup:
- Cloud provider (AWS, GCP, Azure, or DigitalOcean)
- Load balancer for high availability
- Multiple application servers (min. 2 for redundancy)
- Managed PostgreSQL database with replication
- Redis cluster for caching and queues
- CDN for static assets
- Automated backup solution

## Deployment Process

### Manual Deployment

Follow these steps for a manual deployment:

1. Connect to the server via SSH:
   ```bash
   ssh user@your-server-ip
   ```

2. Navigate to the deployment directory:
   ```bash
   cd /var/www/enhanced-laravel-application
   ```

3. Pull the latest code:
   ```bash
   git pull origin main
   ```

4. Install/update dependencies:
   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci
   ```

5. Build frontend assets:
   ```bash
   npm run build
   ```

6. Run database migrations:
   ```bash
   php artisan migrate --force
   ```

7. Clear and rebuild cache:
   ```bash
   php artisan optimize:clear
   php artisan optimize
   php artisan view:cache
   php artisan config:cache
   php artisan route:cache
   ```

8. Restart the FrankenPHP service:
   ```bash
   sudo systemctl restart frankenphp
   ```

9. Verify the deployment:
   ```bash
   curl -I https://your-application-domain.com
   ```

### Automated Deployment with CI/CD

We recommend setting up a CI/CD pipeline using GitHub Actions for automated testing and deployment.

Here's an example GitHub Actions workflow file (`.github/workflows/deploy.yml`):

```yaml
name: Deploy Application

on:
  push:
    branches: [ main ]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - name: Set up PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
          extensions: mbstring, pdo_pgsql, bcmath, redis
      - name: Install dependencies
        run: composer install --prefer-dist
      - name: Run tests
        run: php artisan test

  deploy:
    needs: test
    runs-on: ubuntu-latest
    steps:
      - name: Deploy to production
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.HOST }}
          username: ${{ secrets.USERNAME }}
          key: ${{ secrets.SSH_PRIVATE_KEY }}
          script: |
            cd /var/www/enhanced-laravel-application
            git pull origin main
            composer install --no-dev --optimize-autoloader
            npm ci
            npm run build
            php artisan migrate --force
            php artisan optimize:clear
            php artisan optimize
            php artisan view:cache
            php artisan config:cache
            php artisan route:cache
            sudo systemctl restart frankenphp
```

## Environment Configuration

Proper environment configuration is crucial for a secure and functional deployment. Key environment variables include:

```
APP_NAME="Enhanced Laravel Application"
APP_ENV=production
APP_KEY=base64:your-secure-key
APP_DEBUG=false
APP_URL=https://your-application-domain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=warning

DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-secure-password
DB_SCHEMA=public

BROADCAST_DRIVER=redis
CACHE_DRIVER=redis
FILESYSTEM_DISK=s3
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=your-redis-host
REDIS_PASSWORD=your-redis-password
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=your-mail-host
MAIL_PORT=587
MAIL_USERNAME=your-mail-username
MAIL_PASSWORD=your-mail-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=your-aws-key
AWS_SECRET_ACCESS_KEY=your-aws-secret
AWS_DEFAULT_REGION=your-aws-region
AWS_BUCKET=your-s3-bucket
AWS_USE_PATH_STYLE_ENDPOINT=false
```

## Database Setup

For production environments, follow these steps to set up and optimize the PostgreSQL database:

1. Create a dedicated database user with limited permissions:
   ```sql
   CREATE USER ela_app WITH PASSWORD 'secure-password';
   CREATE DATABASE ela_production;
   GRANT ALL PRIVILEGES ON DATABASE ela_production TO ela_app;
   ```

2. Configure connection pooling for optimal performance:
   ```
   # In postgresql.conf
   max_connections = 100
   shared_buffers = 1GB
   work_mem = 16MB
   maintenance_work_mem = 256MB
   ```

3. Set up regular database backups:
   ```bash
   # Add to crontab
   0 2 * * * pg_dump -U postgres ela_production | gzip > /backup/ela_db_$(date +\%Y\%m\%d).sql.gz
   ```

4. Configure the application to use the database:
   ```
   # In .env
   DB_CONNECTION=pgsql
   DB_HOST=your-db-host
   DB_PORT=5432
   DB_DATABASE=ela_production
   DB_USERNAME=ela_app
   DB_PASSWORD=secure-password
   DB_SCHEMA=public
   ```

## File Storage Configuration

For production, we recommend using Amazon S3 or a compatible object storage service:

1. Create an S3 bucket with appropriate permissions
2. Configure the application to use S3:
   ```
   # In .env
   FILESYSTEM_DISK=s3
   AWS_ACCESS_KEY_ID=your-aws-key
   AWS_SECRET_ACCESS_KEY=your-aws-secret
   AWS_DEFAULT_REGION=your-aws-region
   AWS_BUCKET=your-s3-bucket
   AWS_USE_PATH_STYLE_ENDPOINT=false
   ```

3. Set up a CDN (like CloudFront) in front of your S3 bucket for improved performance

## Caching and Queue Configuration

Redis is recommended for both caching and queue processing:

1. Set up a Redis instance or cluster
2. Configure the application to use Redis:
   ```
   # In .env
   CACHE_DRIVER=redis
   QUEUE_CONNECTION=redis
   SESSION_DRIVER=redis
   REDIS_HOST=your-redis-host
   REDIS_PASSWORD=your-redis-password
   REDIS_PORT=6379
   ```

3. Set up queue workers as systemd services:
   ```
   # /etc/systemd/system/laravel-queue.service
   [Unit]
   Description=Laravel Queue Worker
   After=network.target

   [Service]
   User=www-data
   Group=www-data
   WorkingDirectory=/var/www/enhanced-laravel-application
   ExecStart=/usr/bin/php artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
   Restart=always

   [Install]
   WantedBy=multi-user.target
   ```

4. Enable and start the service:
   ```bash
   sudo systemctl enable laravel-queue
   sudo systemctl start laravel-queue
   ```

## SSL Configuration

Always use HTTPS in production environments:

1. Obtain an SSL certificate (Let's Encrypt is recommended)
2. Configure Nginx with SSL:
   ```nginx
   server {
       listen 80;
       server_name your-application-domain.com;
       return 301 https://$host$request_uri;
   }

   server {
       listen 443 ssl http2;
       server_name your-application-domain.com;

       ssl_certificate /etc/letsencrypt/live/your-application-domain.com/fullchain.pem;
       ssl_certificate_key /etc/letsencrypt/live/your-application-domain.com/privkey.pem;
       ssl_protocols TLSv1.2 TLSv1.3;
       ssl_prefer_server_ciphers on;
       ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384;
       ssl_session_timeout 1d;
       ssl_session_cache shared:SSL:10m;
       ssl_stapling on;
       ssl_stapling_verify on;
       add_header Strict-Transport-Security "max-age=63072000; includeSubDomains; preload";
       add_header X-Frame-Options DENY;
       add_header X-Content-Type-Options nosniff;

       root /var/www/enhanced-laravel-application/public;
       index index.php;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           include fastcgi_params;
       }

       location ~ /\.(?!well-known).* {
           deny all;
       }
   }
   ```

3. Set up auto-renewal for Let's Encrypt certificates:
   ```bash
   # Add to crontab
   0 3 * * * certbot renew --quiet
   ```

## Monitoring and Logging

Implement comprehensive monitoring and logging:

1. Configure Laravel Telescope for development/staging environments
2. Set up Laravel Horizon for queue monitoring
3. Implement application logging to external services:
   ```
   # In .env
   LOG_CHANNEL=stack
   LOG_SLACK_WEBHOOK_URL=your-slack-webhook
   ```

4. Set up server monitoring with tools like New Relic, Datadog, or Prometheus + Grafana
5. Configure error tracking with Sentry or Bugsnag:
   ```
   # In .env
   SENTRY_LARAVEL_DSN=your-sentry-dsn
   ```

## Backup Strategy

Implement a robust backup strategy:

1. Database backups:
   - Daily full backups
   - Hourly incremental backups
   - Store backups off-site (different cloud provider)

2. File storage backups:
   - Regular S3 bucket snapshots
   - Cross-region replication

3. Application code:
   - Git repository serves as code backup
   - Consider GitHub/GitLab repository mirroring

4. Automate backup testing and verification

## Scaling Considerations

As your application grows, consider these scaling strategies:

1. Horizontal scaling:
   - Add more application servers behind a load balancer
   - Implement sticky sessions if needed

2. Database scaling:
   - Read replicas for read-heavy operations
   - Consider database sharding for very large datasets

3. Caching improvements:
   - Implement Redis cluster
   - Add application-level caching for expensive operations

4. CDN integration:
   - Offload static assets to CDN
   - Consider edge caching for dynamic content

## Troubleshooting

Common deployment issues and solutions:

| Issue | Possible Cause | Solution |
|-------|----------------|----------|
| 500 Server Error | Incorrect permissions | Check file ownership and permissions: `chown -R www-data:www-data /var/www/enhanced-laravel-application` |
| Database connection error | Incorrect credentials or firewall | Verify .env settings and check network access |
| Blank white screen | PHP error with display_errors off | Check logs: `tail -f /var/log/nginx/error.log` |
| Queue not processing | Queue worker not running | Restart queue: `sudo systemctl restart laravel-queue` |
| Slow performance | Missing caching or indexing | Enable caching and verify database indexes |
| SSL certificate issues | Expired certificate | Renew certificate: `certbot renew` |

For additional support, consult the Laravel documentation or reach out to the development team.
