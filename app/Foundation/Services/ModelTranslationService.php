<?php

declare(strict_types=1);

namespace App\Foundation\Services;

use App\Foundation\Entities\ModelTranslationDO;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Translation\Translator;

class ModelTranslationService
{
    protected Translator $translator;

    private array $cache = [];

    private string $translationNamespace = 'models';

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function resolve(Model|string $model, string $column, ?string $value = null): ModelTranslationDO
    {
        $class = $model instanceof Model ? get_class($model) : $model;
        if (false === isset($this->cache[$class])) {
            $this->cache[$class] = strtolower(class_basename($class));
        }

        if (null === $value) {
            $value = $model->{$column};
        }

        return new ModelTranslationDO("{$this->translationNamespace}.{$this->cache[$class]}.{$column}", $value);
    }
}
