<?php

return [
    'columns' => [
        'description' => [
            'label' => '操作信息',
        ],
        'log_name' => [
            'label' => '类型',
        ],
        'event' => [
            'label' => '事件',
        ],
        'subject_type' => [
            'label' => '操作对象',
        ],
        'causer' => [
            'label' => '操作人',
        ],
        'properties' => [
            'label' => '属性',
        ],
        'created_at' => [
            'label' => '记录时间',
        ],
    ],
    'filters' => [
        'created_at' => [
            'label'         => '记录时间',
            'created_from'  => '创建时间从',
            'created_until' => '创建时间到',
        ],
        'event' => [
            'label' => '事件',
        ],
    ],
];
