@servers(['test' => ['root@91.200.242.153']])

@setup
    $repo = 'git@github.com:Wsmallnews/tenancy.git';
    $branch = $branch ?? 'v4';
    $appDir = '/www/wwwroot/resource-dbv4.eep.ink';  // 服务器项目目录
    $env = $env ?? 'local'; // 环境变量
@endsetup


@story('deploy')
    update-code
    install-dependencies
    run-migrate
    run-npm
    cache-reload
@endstory


{{-- 更新代码 --}}
@task('update-code', ['on' => ['test'], 'parallel' => true])
    cd {{ $appDir }}
    git config --global --add safe.directory {{ $appDir }}
    git pull origin {{ $branch }}
@endtask

{{-- 安装依赖 --}}
@task('install-dependencies', ['on' => ['test'], 'parallel' => true])
    cd {{ $appDir }}
    composer install
    chown -R www:www {{ $appDir }}
@endtask

{{-- 执行迁移 --}}
@task('run-migrate', ['on' => ['test'], 'parallel' => true])
    cd {{ $appDir }}
    php artisan migrate --force
@endtask


{{-- 执行 npm --}}
@task('run-npm', ['on' => ['test'], 'parallel' => true])
    cd {{ $appDir }}
    npm install
    npm run build
    chown -R www:www {{ $appDir }}
@endtask


@task('cache-reload', ['on' => ['test'], 'parallel' => true])
    cd {{ $appDir }}
    php artisan optimize:clear
    php artisan filament:optimize-clear
    
    @if ($env === 'production')
        php artisan optimize
        php artisan filament:optimize
    @endif
    php artisan icons:cache
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