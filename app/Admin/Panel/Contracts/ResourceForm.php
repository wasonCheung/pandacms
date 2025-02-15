<?php

declare(strict_types=1);

namespace App\Admin\Panel\Contracts;

use App\Foundation\Entities\TranslationDO;
use Filament\Forms\Form;

abstract class ResourceForm
{
    public const OPERATION_CREATE = 'create';

    public const OPERATION_EDIT = 'edit';

    protected readonly Form $form;

    public static function getTranslation(string $key): TranslationDO
    {
        return __class(static::class, $key);
    }

    public static function make(Form $form): Form
    {
        return app(static::class)
            ->form($form)
            ->buildForm();
    }

    public function buildForm(): Form
    {
        return $this->isEditForm() ? $this->buildEditForm() : $this->buildCreateForm();
    }

    public function isEditForm(): bool
    {
        return $this->form->getOperation() === static::OPERATION_EDIT;
    }

    public function buildEditForm(): Form
    {
        return $this->form;
    }

    public function buildCreateForm(): Form
    {
        return $this->form;
    }

    public function form(Form $form): static
    {
        $this->form = $form;

        return $this;
    }

    public function isCreateForm(): bool
    {
        return $this->form->getOperation() === static::OPERATION_CREATE;
    }
}
