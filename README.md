### Quick Start

#### 安装扩展包
```
composer install
```

#### 配置 .env

##### 复制 .env

将 .env.example 复制 并改名为 .env

##### 修改 mysql 配置

```
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

改为

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tenancy
DB_USERNAME=root
DB_PASSWORD=root
```

##### 日志配置

```
LOG_STACK=single
```

改为

```
LOG_STACK=daily
```

##### 生成 .env 密钥
```
php artisan key:genrate
```


##### 创建 Team

直接修改数据库 team 和 team_user 两个表


##### 创建租户超级管理员

```
--user=[指定id,不指定则列出user列表选择; 用户少的时候可不制定]
--panel=[指定panel,不指定则是 default panel]
--tenant=指定租户

php artisan shield:super-admin --user=1 --tenant=1
```

开启超级管理员守卫



### 创建主题

```
php artisan make:filament-theme admin
```

在 vite.config.js 中添加主题编译文件

```
...

        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),

...

```

改为

```
...

        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/filament/admin/theme.css'],
            refresh: true,
        }),

...

```

将主题添加到 panel

```
->viteTheme('resources/css/filament/admin/theme.css')
```

重新运行编译

```
npm run build

```


### permission

filament-shield 扩展包会在 AppServiceProvider 中重新给 laravel-permission 扩展包注册自定义 role 和 permission 表，所以可以不用 改 permission.php 配置文件了

如果使用多租户，filament-shield 会自动把 laravel-permission 改为多租户模式， permission.php 的 teams => true