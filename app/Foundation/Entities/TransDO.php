<?php

declare(strict_types=1);

namespace App\Foundation\Entities;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Lang;
use InvalidArgumentException;
use Stringable;

class TransDO implements Htmlable, Stringable
{
    private ?string $locale = null;

    private string $key;

    private ?string $notFound = null;

    private array $replacements = [];

    private bool $fallback = false;

    public function fallback(bool $fallback = true): self
    {
        $this->fallback = $fallback;

        return $this;
    }

    public function notFound(?string $notFound): self
    {
        $this->notFound = $notFound;

        return $this;
    }

    public function getNotFound(): ?string
    {
        return $this->notFound;
    }

    public function replacements(array $replacements): self
    {
        $this->replacements = $replacements;

        return $this;
    }

    public function locale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getReplacements(): array
    {
        return $this->replacements;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function isFallback(): bool
    {
        return $this->fallback;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * case 1: return the translation if it exists
     * case 2: return the key if the translation does not exist and fallback is false
     * case 3: return the not found message if the translation does not exist and fallback is true
     */
    public function getTranslation(): string|array
    {
        if (Lang::has($this->getKey(), $this->getLocale())) {
            return Lang::get($this->getKey(), $this->getReplacements(), $this->getLocale());
        }

        return $this->isFallback() ? $this->getNotFound() : $this->getKey();
    }

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public static function make(string $key): self
    {
        return new self($key);
    }

    public function toHtml(): string
    {
        return $this->__toString();
    }

    public function __toString(): string
    {
        $msg = $this->getTranslation();

        if (is_string($msg)) {
            return $msg;
        }
        throw new InvalidArgumentException(sprintf('TransDO::__toString() Failed! Result is not a string for key: %s',
            $this->getKey()));
    }
}
