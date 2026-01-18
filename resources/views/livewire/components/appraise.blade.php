<div class="w-full flex flex-col gap-4">
    <div class="w-full flex justify-end">
        @if (($this->applyAction)(['appraise_id' => $appraise->id])->isVisible())
            {{ ($this->applyAction)(['appraise_id' => $appraise->id]) }}
        @endif
    </div>

    {{ $this->appraiseInfolist }}
</div>