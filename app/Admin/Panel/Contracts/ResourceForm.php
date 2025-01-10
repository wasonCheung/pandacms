<?php

declare(strict_types=1);

namespace App\Admin\Panel\Contracts;

use Filament\Forms\Form;

abstract class ResourceForm
{
    public const OPERATION_CREATE = 'create';

    public const OPERATION_EDIT = 'edit';

    public function __construct(protected Form $form) {}

    public function isEditForm(): bool
    {
        return $this->form->getOperation() === static::OPERATION_EDIT;
    }

    public function isCreateForm(): bool
    {
        return $this->form->getOperation() === static::OPERATION_CREATE;
    }

    abstract public function buildEditForm(): Form;

    abstract public function buildCreateForm(): Form;

    public function buildForm(): Form
    {
        return $this->isEditForm() ? $this->buildEditForm() : $this->buildCreateForm();
    }

    public static function make(Form $form): Form
    {
        return app(static::class, ['form' => $form])->buildForm();
    }
}
