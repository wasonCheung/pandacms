<?php

declare(strict_types=1);

namespace App\Admin\Panel\Resources\Role;

use App\Admin\Panel\Contracts\ResourceForm;
use App\Admin\Panel\Resources\RoleResource;
use App\Admin\Panel\Services\PermissionsFormService;
use App\Foundation\Enums\DefaultGuard;
use App\Foundation\Models\Role;
use App\Foundation\Services\RoleService;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

/**
 * @see RoleResource
 */
class RoleForm extends ResourceForm
{
    public const REGEX_NAME = '/^[a-z0-9]+$/';

    public function buildEditForm(): Form
    {
        return $this->form->schema([
            Section::make()
                ->schema([
                    $this->nameField()
                        ->disabled(fn (Role $record) => app(RoleService::class)->isDefaultRole($record)),
                    $this->guardNameField()
                        ->disabled(fn (Role $record) => app(RoleService::class)->isDefaultRole($record)),
                ])->columns([
                    'sm' => 2,
                    'lg' => 3,
                ]),
            Grid::make()
                ->hidden(fn (Role $record) => app(RoleService::class)->isSuperAdmin($record))
                ->schema($this->permissionsField()),
        ])
            ->disabled(fn (Role $record) => app(RoleService::class)->isSuperAdmin($record));
    }

    public function nameField(): Component
    {
        return TextInput::make('name')
            ->hintIcon('heroicon-o-language')
            ->hintIconTooltip(function ($state) {
                return __model(Role::class, 'name', $state ?: 'xxx')->fallback(false);
            })
            ->required()
            ->label(__class(__CLASS__, 'name'))
            ->unique(ignoreRecord: true)
            ->regex(self::REGEX_NAME)
            ->validationMessages([
                'regex' => __class(__CLASS__, 'name_regex'),
            ])
            ->live()
            ->maxLength(10);
    }

    public function guardNameField(): Component
    {
        return Select::make('guard_name')
            ->required()
            ->live()
            ->label(__class(__CLASS__, 'guard_name'))
            ->options(DefaultGuard::class);
    }

    public function permissionsField(): array
    {
        return app(PermissionsFormService::class)->getComponents();
    }

    public function buildCreateForm(): Form
    {
        return $this->form->schema([
            Section::make()
                ->schema([
                    $this->nameField(),
                    $this->guardNameField(),
                ])->columns([
                    'sm' => 2,
                    'lg' => 3,
                ]),
            Grid::make()->schema($this->permissionsField()),
        ]);
    }
}
