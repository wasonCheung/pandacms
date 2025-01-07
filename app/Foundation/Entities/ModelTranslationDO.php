<?php

declare(strict_types=1);

namespace App\Foundation\Entities;

use Illuminate\Support\Facades\Lang;

class ModelTranslationDO
{
    public readonly ?string $value;
    public readonly string $translationKey;

    private bool $fallback = false;

    public function __construct(string $translationKey, ?string $value = null)
    {
        $this->translationKey = $value ? $translationKey.'.'.$value : $translationKey;
        $this->value = $value;
    }

    public function trans(?string $locale = null): string|array|null
    {
        if (Lang::has($this->translationKey, $locale)) {
            return Lang::get($this->translationKey, [], $locale);
        }

        return $this->fallback ? $this->value : $this->translationKey;
    }

    /**
     * if the translation key is not found, return the value.
     *
     * @return $this
     */
    public function fallback(bool $fallback = true): self
    {
        $this->fallback = $fallback;

        return $this;
    }
}
