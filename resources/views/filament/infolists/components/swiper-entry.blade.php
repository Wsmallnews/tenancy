@php
    use Illuminate\Support\Arr;
    use Illuminate\Support\Collection;

    $state = $getState();
    $state = Arr::wrap($state);
    $stateCount = count($state);
    $tooltip = $getEmptyTooltip();
    $placeholder = $getPlaceholder();

    if ($state instanceof Collection) {
        $state = $state->all();
    }

    $images = [];
    foreach ($state as $uuid) {
        $images[] = $getImageUrl($uuid);
    }
@endphp


<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    @if (blank($state))
        <div
            {{
                $attributes
                    ->merge($entry?->getExtraAttributes() ?? [], escape: false)
                    ->merge([
                        'x-tooltip' => filled($tooltip)
                            ? '{
                                content: ' . Js::from($tooltip) . ',
                                theme: $store.theme,
                            }'
                            : null,
                    ], escape: false)
                    ->class([
                        'fi-in-swiper',
                    ])
            }}
        >
            @if (filled($placeholder))
                <p class="fi-in-placeholder">
                    {{ $placeholder }}
                </p>
            @endif
        </div>
    @else
        <x-swiper
            :images="$images"
            :hasThumb="false"
        />
    @endif


</x-dynamic-component>
