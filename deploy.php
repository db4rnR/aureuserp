<?php

declare(strict_types=1);

namespace Deployer;

require 'recipe/laravel.php';

// Config
set('repository', 'git@github.com:300-aureuserp/300-aureuserp.git');
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
task('build', function (): void {
    run('cd {{release_path}} && npm ci');
    run('cd {{release_path}} && npm run build');
});

// Hooks
after('deploy:failed', 'deploy:unlock');
after('deploy:vendors', 'build');
