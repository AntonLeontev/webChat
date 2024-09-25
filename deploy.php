<?php

namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'https://github.com/AntonLeontev/webChat.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('195.246.231.120')
    ->set('port', '7381')
    ->set('remote_user', 'djsiwozbbxu')
    ->set('deploy_path', '~/chat');

task('build', function () {
    cd('{{release_path}}');
    run('npm install');
    run('npm run build');
});

task('config:clear', function () {
    cd('~/chat/current');
    run('php artisan config:clear');
    run('php artisan config:cache');
});

task('route:clear', function () {
    cd('~/chat/current');
    run('php artisan route:clear');
    run('php artisan route:cache');
});

task('cache:clear', function () {
    cd('~/chat/current');
    run('php artisan cache:clear');
});

// Hooks
after('deploy:publish', 'build');

after('deploy:failed', 'deploy:unlock');
