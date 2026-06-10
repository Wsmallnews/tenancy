@php
    use Filament\Support\Icons\Heroicon;
    use Wsmallnews\Cms\Support\Utils as CmsUtils;
@endphp

<div class="sn-container overflow-hidden">
    {{-- 手风琴分类选择（无标题） --}}
    <x-accordion :contained="false">
        @foreach($categories as $index => $category)
            @php
                $children = $category->children->filter(fn ($c) => $c->status === \Wsmallnews\Category\Enums\CategoryStatus::Normal);
                $categoryCover = $category->getFirstMediaUrl('cover');
            @endphp

            <x-accordion-item :expanded="$loop->first" icon="heroicon-o-folder">
                <x-slot:heading>
                    {{-- 分类封面图 --}}
                    <div class="size-8 shrink-0 rounded-md overflow-hidden bg-gray-100 dark:bg-gray-800">
                        @if ($categoryCover)
                            <img src="{{ $categoryCover }}" alt="{{ $category->name }}" class="size-full object-cover" loading="lazy" />
                        @else
                            <div class="sn-image-placeholder">
                                <x-filament::icon :icon="Heroicon::OutlinedPhoto" class="size-4" aria-hidden="true" />
                            </div>
                        @endif
                    </div>
                    <span class="font-medium text-sm text-gray-900 dark:text-gray-100 truncate">{{ $category->name }}</span>
                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                        {{ $children->count() }} 个分类
                    </span>
                </x-slot:heading>

                @if($children->isEmpty())
                    <p class="sn-tip-text py-2">暂无子分类</p>
                @else
                    <div class="flex flex-wrap gap-2">
                        @foreach($children as $child)
                            @php
                                $childCover = $child->getFirstMediaUrl('cover');
                            @endphp
                            <a
                                href="{{ CmsUtils::route('appraises', ['categoryId' => $child->id]) }}"
                                class="sn-btn sn-btn-md sn-btn-outline"
                            >
                                {{-- 子分类封面缩略图 --}}
                                <div class="size-5 shrink-0 rounded overflow-hidden bg-gray-100 dark:bg-gray-800">
                                    @if ($childCover)
                                        <img src="{{ $childCover }}" alt="{{ $child->name }}" class="size-full object-cover" loading="lazy" />
                                    @else
                                        <div class="sn-image-placeholder">
                                            <x-filament::icon :icon="Heroicon::OutlinedPhoto" class="size-3" aria-hidden="true" />
                                        </div>
                                    @endif
                                </div>
                                {{ $child->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </x-accordion-item>
        @endforeach
    </x-accordion>
</div>
