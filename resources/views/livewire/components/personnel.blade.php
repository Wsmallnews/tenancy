@php
    use Filament\Support\Icons\Heroicon;
@endphp

<article class="sn-container w-full flex flex-col gap-6 p-4 md:p-6">
    <h1 class="sn-h2-text">
        {{ $personnel->name }}
    </h1>

    <section class="flex flex-col md:flex-row gap-6 sn-gray-bg p-4 md:p-6 rounded-md">
        @if ($personnel->getFirstMediaUrl('avatar'))
            <div class="w-full md:w-48 shrink-0 rounded-md overflow-hidden bg-gray-100 dark:bg-gray-800">
                <img
                    src="{{ $personnel->getFirstMediaUrl('avatar') }}"
                    alt="{{ $personnel->name }}"
                    class="w-full h-auto object-cover"
                />
            </div>
        @else
            <div class="w-full md:w-48 aspect-5/7 shrink-0 rounded-md sn-image-placeholder">
                <x-filament::icon :icon="Heroicon::OutlinedUser" class="w-16 h-16" aria-hidden="true" />
            </div>
        @endif

        <dl class="flex flex-col gap-3 grow min-w-0">
            @php
                $fields = [
                    ['label' => '学历', 'value' => $personnel->qualification],
                    ['label' => '职称', 'value' => $personnel->professional_title],
                    ['label' => '研究方向', 'value' => $personnel->research_focus],
                    ['label' => '研究成果', 'value' => $personnel->research_result],
                    ['label' => '个人简介', 'value' => $personnel->intro, 'block' => true],
                ];
            @endphp
            @foreach ($fields as $field)
                @if ($field['value'])
                    <div @class([
                        'flex gap-2.5 leading-7',
                        'items-center line-clamp-1' => empty($field['block']),
                    ])>
                        <dt class="sn-tip-text shrink-0 min-w-20">{{ $field['label'] }}</dt>
                        <dd class="sn-content-text font-semibold text-gray-800 dark:text-gray-200">{{ $field['value'] }}</dd>
                    </div>
                @endif
            @endforeach
        </dl>
    </section>

    @if ($personnel->content?->content)
        <div class="sn-prose">
            {!! $personnel->content?->content !!}
        </div>
    @endif
</article>
