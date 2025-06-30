<?php

namespace App\Features;

use Closure;
use Filament\Forms\Components;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class NavigationType
{

    protected ?Collection $types;

    public function __construct()
    {
        $this->types = collect();
    }


    public static function make(): static
    {
        app()->singletonIf(static::class, static::class);

        return app(static::class);
    }


    public function register($typeInfo): static
    {
        $type = $typeInfo['type'];
        $this->types->put($type, $typeInfo);

        return $this;
    }


    public function registers($typeInfos): static
    {
        foreach ($typeInfos as $typeInfo) {
            $this->register($typeInfo);
        }

        return $this;
    }



    public function getTypes(): Collection
    {
        return $this->types;
    }


    public function getType($type): array
    {
        return $this->types->firstWhere('type', $type);
    }


    public function getOptions()
    {
        return $this->types->mapWithKeys(function ($typeInfo) {
            return [$typeInfo['type'] => $typeInfo['label']];
        });
    }


    public function hasForms($type, $arguments = [])
    {
        $forms = $this->getTypeForms($type, $arguments);

        return $forms && count($forms) > 0;
    }


    public function getTypeForms($type, $arguments = [])
    {
        $typeInfo = $this->types->firstWhere('type', $type);

        $forms = $typeInfo['forms'] ?? [];

        return $forms instanceof Closure ? app()->call($forms, $arguments) : $forms;
    }
}
