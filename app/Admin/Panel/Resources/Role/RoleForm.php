<?php

declare(strict_types=1);

namespace App\Admin\Panel\Resources\Role;

use App\Admin\Panel\Contracts\ResourceForm;
use App\Admin\Panel\Services\PermissionsFormService;
use App\Foundation\Enums\DefaultGuard;
use App\Foundation\Models\Role;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class RoleForm extends ResourceForm
{
    public function buildEditForm(): Form
    {
        return $this->form->schema([
            Section::make()
                ->schema([
                    $this->nameField()
                        ->disabled(fn (Role $record) => $record->isDefaultRole()),
                    $this->guardNameField()
                        ->disabled(fn (Role $record) => $record->isDefaultRole()),
                ])->columns([
                    'sm' => 2,
                    'lg' => 3,
                ]),
            Grid::make()
                ->hidden(fn (Role $record) => $record->isSuperAdminRole())
                ->schema($this->permissionsField()),
        ])
            ->disabled(fn (Role $record) => $record->isSuperAdminRole());
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
            ->regex('/^[a-z0-9]+$/')
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
            ->options($this->getGuardNameFieldOptions());
    }

    protected function getGuardNameFieldOptions(): array
    {
        return collect(DefaultGuard::cases())
            ->flatMap(fn ($guard) => [
                $guard->value => __enum($guard),
            ])->toArray();
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
