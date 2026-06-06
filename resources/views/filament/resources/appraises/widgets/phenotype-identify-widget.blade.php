@php
    $data = $this->getWidgetData();
    $records = $data['records'];
    $categoryFields = $data['categoryFields'];
    $hasRecords = $records->isNotEmpty();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            表型鉴定记录
        </x-slot>

        @if(!$hasRecords)
            <div class="sn-empty sn-compact">
                <div class="sn-empty-icon-bg sn-empty-icon-sm sn-empty-icon-primary">
                    <x-filament::icon icon="heroicon-o-document-text" class="size-5" />
                </div>
                <p class="sn-empty-description">暂无表型鉴定数据</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($records as $index => $record)
                    <div class="sn-container sn-hover overflow-hidden">
                        {{-- 记录标题 --}}
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-white/10">
                            <div class="flex items-center gap-2">
                                <span class="sn-h4-text">{{ $record->name ?: '鉴定 #' . ($index + 1) }}</span>
                                @php
                                    $statusClass = match($record->status->value) {
                                        'normal' => 'sn-badge-success',
                                        'hidden' => 'sn-badge-gray',
                                        default => 'sn-badge-gray',
                                    };
                                @endphp
                                <span class="sn-badge {{ $statusClass }}">{{ $record->status->getLabel() }}</span>
                            </div>
                            <span class="sn-tip-text">{{ $record->created_at?->format('Y-m-d H:i') }}</span>
                        </div>

                        {{-- 自定义字段展示 --}}
                        @if(!empty($categoryFields))
                            <div class="p-4 space-y-3">
                                @foreach($categoryFields as $groupKey => $group)
                                    @php
                                        $groupFields = $group['fields'] ?? [];
                                        $hasValues = false;
                                        foreach ($groupFields as $subKey => $subField) {
                                            $value = data_get($record, "options.fields.{$groupKey}.fields.{$subKey}.data.value");
                                            if (!empty($value)) {
                                                $hasValues = true;
                                                break;
                                            }
                                        }
                                    @endphp

                                    @if($hasValues)
                                        @if(!$loop->first)
                                            <div class="border-t border-gray-100 dark:border-white/5"></div>
                                        @endif

                                        <div>
                                            <h4 class="sn-tip-text uppercase tracking-wider mb-2">{{ $group['name'] }}</h4>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                                                @foreach($groupFields as $subKey => $subField)
                                                    @php
                                                        $value = data_get($record, "options.fields.{$groupKey}.fields.{$subKey}.data.value");
                                                        $fieldType = $subField['type'] ?? 'textInput';
                                                        $fieldData = $subField['data'] ?? [];
                                                        $label = $fieldData['name'] ?? '';
                                                        $unit = $fieldData['unit'] ?? '';
                                                    @endphp

                                                    @if($fieldType === 'upload_image')
                                                        @if($value)
                                                            <div class="col-span-full">
                                                                <span class="sn-tip-text">{{ $label }}</span>
                                                                <div class="mt-1 flex flex-wrap gap-2">
                                                                    @php
                                                                        $mediaItems = $record->getMedia($fieldData['collection_name'] ?? '');
                                                                    @endphp
                                                                    @foreach($mediaItems as $media)
                                                                        <img
                                                                            src="{{ $media->getUrl() }}"
                                                                            alt="{{ $label }}"
                                                                            class="size-16 rounded-md object-cover ring-1 ring-gray-200 dark:ring-white/10"
                                                                        />
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @elseif($fieldType === 'select')
                                                        @if($value)
                                                            <div>
                                                                <span class="sn-tip-text">{{ $label }}</span>
                                                                <p class="sn-content-text">
                                                                    {{ is_array($value) ? implode(', ', $value) : $value }}
                                                                    @if($unit)<span class="sn-gray-text ml-1">{{ $unit }}</span>@endif
                                                                </p>
                                                            </div>
                                                        @endif
                                                    @else
                                                        @if(!empty($value) && $value !== null)
                                                            <div>
                                                                <span class="sn-tip-text">{{ $label }}</span>
                                                                <p class="sn-content-text">
                                                                    {{ $value }}
                                                                    @if($unit)<span class="sn-gray-text ml-1">{{ $unit }}</span>@endif
                                                                </p>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="px-4 py-3 sn-descript-text">无自定义字段数据</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
