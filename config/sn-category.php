<?php

use Wsmallnews\Category\Filament\Pages\Category\CategoryPage;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\CategoryTypeResource;
use Wsmallnews\Category\Models;

return [
    /**
     * Default scopeable
     */
    'scopeable' => [
        'scope_type' => 'sn-category',
        'scope_id' => 0,
    ],

    /**
     * Custom models
     */
    'models' => [
        'category' => Models\Category::class,
        'category_type' => Models\CategoryType::class,
    ],

    /**
     * Panel register
     */
    'panel_register' => [
        'pages' => [
            // CategoryPage::class,
        ],
        'resources' => [
            // CategoryTypeResource::class,
        ],
    ],

    /**
     * 文件基础目录，会自动拼接当前年月日 (仅用于 filament 默认上传组件 (Forms\Components\FileUpload))
     */
    'file_directory' => 'sn/categories/',

];
