<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'git@gitlab.com:secomapp/development/lai-qa-app.git');
set('update_code_strategy', 'clone');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('rvih')
    ->set('remote_user', 'apps')
    ->set('deploy_path', '/var/www/qa');

// Hooks

after('deploy:failed', 'deploy:unlock');
