<?php

namespace App\Enums\Traits;

trait EnumHelper
{
    /**
     * 获取枚举数组
     *
     * @param  bool  $is_kv  是否是 key-value 模式，默认否
     */
    public static function labels($is_kv = false): array
    {
        $values = collect(self::cases())
            ->map(function ($enum) {
                return [
                    'name' => $enum->getLabel(),
                    'value' => $enum->value,
                ];
            })->toArray();

        if ($is_kv) {
            $values = array_column($values, 'name', 'value');
        }

        return $values;
    }

    /**
     * 获取枚举图标
     *
     * @param  bool  $is_kv  是否是 key-value 模式，默认否
     */
    public static function getIcons($is_kv = false)
    {
        $values = collect(self::cases())
            ->map(function ($enum) {
                return [
                    'name' => $enum->value,
                    'value' => $enum->getIcon(),
                ];
            })->toArray();

        if ($is_kv) {
            $values = array_column($values, 'value', 'name');
        }

        return $values;
    }

    /**
     * 获取枚举颜色
     *
     * @param  bool  $is_kv  是否是 key-value 模式，默认否
     */
    public static function getColors($is_kv = false)
    {
        $values = collect(self::cases())
            ->map(function ($enum) {
                return [
                    'name' => $enum->value,
                    'value' => $enum->getColor(),
                ];
            })->toArray();

        if ($is_kv) {
            $values = array_column($values, 'value', 'name');
        }

        return $values;
    }
}
