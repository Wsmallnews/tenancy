<div class="w-full flex flex-col gap-4">
    <div class="w-full flex justify-end">
        {{ ($this->applyAction)(['appraise_id' => $appraise->id]) }}
    </div>

    {{ $this->appraiseInfolist }}
</div>