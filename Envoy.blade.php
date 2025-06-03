@servers(['test' => ['root@169.197.82.139']])

@setup
    $repo = 'git@github.com:Wsmallnews/tenancy.git';
    $branch = $branch ?? 'main';
    $appDir = '/www/wwwroot/resource-db.eep.ink';  // 服务器项目目录
    $env = $env ?? 'local'; // 环境变量
@endsetup


@story('deploy')
    update-code
    install-dependencies
    run-migrate
    cache-reload
@endstory


{{-- 更新代码 --}}
@task('update-code', ['on' => ['test'], 'parallel' => true, 'confirm' => true])
    cd {{ $appDir }}
    git config --global --add safe.directory /www/wwwroot/resource-db.eep.ink
    git pull origin {{ $branch }}
@endtask

{{-- 安装依赖 --}}
@task('install-dependencies', ['on' => ['test'], 'parallel' => true, 'confirm' => true])
    cd /www/wwwroot/resource-db.eep.ink
    composer install
@endtask

{{-- 执行迁移 --}}
@task('run-migrate', ['on' => ['test'], 'parallel' => true, 'confirm' => true])
    cd /www/wwwroot/resource-db.eep.ink
    php artisan migrate --force
@endtask


@task('cache-reload', ['on' => ['test'], 'parallel' => true])
    cd {{ $appDir }}
    php artisan optimize:clear
    php artisan filament:optimize-clear
    
    @if ($env === 'production')
        php artisan optimize
        php artisan filament:optimize
    @endif
@endtask


@task('app-init', ['on' => ['test'], 'parallel' => true])
    cd {{ $appDir }}
    php artisan storage:link
@endtask


{{-- @task('create-team', ['on' => ['test'], 'parallel' => true])
    cd {{ $appDir }}
@endtask


@task('user-role', ['on' => ['test'], 'parallel' => true])
    cd {{ $appDir }}
    php artisan make:filament-user

    php artisan shield:super-admin --user=1 --tenant=1
@endtask --}}











php artisan migrate --force


@task('restart-queues', ['on' => 'test'])
cd /home/user/example.com
php artisan queue:restart
@endtask