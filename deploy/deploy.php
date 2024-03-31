<?php

namespace Deployer;

require_once 'recipe/common.php';

inventory(file_exists('deploy/hosts.local.yml') ? 'deploy/hosts.local.yml' : 'deploy/hosts.yml');

set('application', 'blog.bpystep.ru');

set('repository', 'git@github.com:bpystep/blog.site.git');

set('shared_dirs', [
    'common/runtime',
    'common/web/storage',
    'admin/runtime',
    'public/runtime',
    'console/runtime'
]);

set('copy_dirs', [
    'vendor'
]);

add('writable_dirs', [
    'common/runtime',
    'common/web',
    'common/web/storage',
    'admin/runtime',
    'admin/web',
    'public/runtime',
    'public/web',
    'console/runtime'
]);

set('allow_anonymous_stats', false);

set('shared_files', [
    '.env',
    'common/config/web.local.php',
    'common/config/params.local.php',
    'console/config/console.local.php',
    'console/config/params.local.php',
    'admin/config/web.local.php',
    'admin/config/params.local.php',
    'public/config/web.local.php',
    'public/config/params.local.php'
]);

task('deploy:run_migrations', function () {
    run('{{bin/php}} {{release_path}}/console/yii migrate --interactive=0');
})->desc('Run migrations');

task('deploy:clear_cache', function () {
    run('php {{release_path}}/console/yii cache/flush-all');
})->desc('Clear the Yii cache');

task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:copy_dirs',
    'deploy:vendors',
    'deploy:shared',
    'deploy:writable',
    'deploy:run_migrations',
    'deploy:clear_cache',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
])->desc('Deploy blog');

after('deploy', 'success');

after('deploy:failed', 'deploy:unlock');
