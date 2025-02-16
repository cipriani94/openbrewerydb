<?php

namespace App\Models\External;

use Illuminate\Support\Collection;

abstract class AbstractExternalModel
{
    protected array $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function getAttribute($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}
