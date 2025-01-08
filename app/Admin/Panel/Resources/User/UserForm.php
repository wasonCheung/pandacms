<?php

declare(strict_types=1);

namespace App\Admin\Panel\Resources\User;

use App\Admin\Panel\ResourceForm;
use Filament\Forms\Form;

class UserForm extends ResourceForm
{
    public function buildEditForm(): Form
    {
        return $this->form;
    }

    public function buildCreateForm(): Form
    {
        return $this->form;
    }
}
