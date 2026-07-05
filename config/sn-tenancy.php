<?php

return [

    /*
    |--------------------------------------------------------------------------
    | 租户域名配置
    |--------------------------------------------------------------------------
    |
    | 本地开发留空，使用路径模式访问：tenancyv4.test/tenant/first/...
    | 线上设置完整域名，使用子域名模式：first.resource-dbv4.eep.ink
    |
    | tenant_domain  — Admin 面板 + 前台路由的租户域名模板
    | platform_domain — Platform 面板的固定域名
    |
    */

    'cms_domain' => env('CMS_DOMAIN'),

    'platform_domain' => env('PLATFORM_DOMAIN'),

];
