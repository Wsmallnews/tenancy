{{-- 种质资源搜索结果条目（经来源注册的 view 选项使用，结构同 appraises 列表页 list 样式条目）
    数据契约：$result（SearchResult，含 ->record 原始 Appraise 模型）、$query 关键词（高亮用 text_highlight() 助手）；
    外层的链接包裹由 support 统一处理（外层 <a> 行带 sn-link hover 联动），本视图只负责条目内容区。
    同时用于头部搜索下拉浮层与搜索结果页：图片比列表条目小一档，兼顾浮层紧凑度。 --}}

@php
    use Filament\Support\Icons\Heroicon;

    $appraise = $result->record;
@endphp

<div class="flex flex-row items-center gap-3 sm:gap-4 grow min-w-0 text-left">
    <div class="w-24 sm:w-32 aspect-video shrink-0 rounded-md overflow-hidden bg-gray-100 dark:bg-gray-800">
        @if ($appraise && $appraise->getFirstMediaUrl('cover'))
            <img
                class="sn-motion-scale w-full h-full object-cover"
                src="{{ $appraise->getFirstMediaUrl('cover') }}"
                alt="{{ $result->title }}"
                loading="lazy"
            />
        @else
            <div class="sn-image-placeholder sn-motion-scale">
                <x-filament::icon :icon="Heroicon::OutlinedPhoto" class="w-8 h-8" aria-hidden="true" />
            </div>
        @endif
    </div>

    <div class="flex flex-col grow min-w-0 gap-1">
        <div class="sn-h3-text sn-hover line-clamp-1 transition duration-300">
            {!! text_highlight($result->title, $query ?? '') !!}
        </div>

        {{-- 保存单位（同 appraises 列表条目的副标题） --}}
        @if ($result->description)
            <div class="sn-descript-text line-clamp-2">{!! text_highlight($result->description, $query ?? '') !!}</div>
        @endif

        {{-- 底行：日期，贴底对齐（同 appraises 列表条目） --}}
        @if ($appraise?->updated_at)
            <div class="sn-tip-text mt-auto">
                <time datetime="{{ $appraise->updated_at->toIso8601String() }}">{{ $appraise->updated_at->format('Y-m-d') }}</time>
            </div>
        @endif
    </div>
</div>
