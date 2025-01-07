<?php

declare(strict_types=1);

namespace App\Admin\Panel\Resources;

use App\Admin\Panel\Resources\Role\Pages\CreateRole;
use App\Admin\Panel\Resources\Role\Pages\EditRole;
use App\Admin\Panel\Resources\Role\Pages\ListRoles;
use App\Admin\Utils\PermissionFormUtil;
use App\Base\Enums\GuardsName;
use App\Foundation\Models\Role;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Translation\Translator;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->hintIcon('heroicon-o-language')
                            ->hintIconTooltip(function ($state) {
                                return Translator::model(Role::class, 'name', $state)->trans();
                            })
                            ->disabled(fn ($record) => $record?->isDefaultRoles())
                            ->required()
                            ->label(__('admin.resources.role.form.name'))
                            ->unique(ignoreRecord: true)
                            ->regex('/^[a-z0-9]+$/')
                            ->validationMessages([
                                'regex' => __('admin.resources.role.form.name_regex'),
                            ])
                            ->maxLength(10),
                        Select::make('guard_name')
                            ->required()
                            ->label(__('admin.resources.role.form.guard_name'))
                            ->disabled(fn ($record) => $record?->isDefaultRoles())
                            ->options(function () {
                                return collect(GuardsName::cases())
                                    ->flatMap(fn ($guard) => [
                                        $guard->value => Translator::model(Role::class,
                                            'guard_name', $guard->value)->trans(),
                                    ])->toArray();
                            })
                            ->live(),
                    ])
                    ->columns([
                        'sm' => 2,
                        'lg' => 3,
                    ]),
                Grid::make()
                    ->schema(fn (Get $get) => match ($get('guard_name')) {
                        GuardsName::Admin->value => [
                            PermissionFormUtil::getFormComponent(),
                        ],
                        default => [],
                    }),
            ]);
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.role.model_label');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('guard_name')
            ->striped()
            ->paginated(false)
            ->columns([
                TextColumn::make('name')
                    ->state(fn ($record) => Translator::model($record, 'name')->fallback()->trans())
                    ->label(__('admin.resources.role.columns.name')),
                TextColumn::make('guard_name')
                    ->color(fn ($record) => match ($record->guard_name) {
                        GuardsName::Admin->value => 'danger',
                        GuardsName::Portal->value => 'success',
                        GuardsName::Api->value => 'info',
                    })
                    ->badge()
                    ->state(fn ($record,
                    ) => Translator::model($record, 'guard_name')->fallback()->trans())
                    ->label(__('admin.resources.role.columns.guard_name')),
                TextColumn::make('permissions')
                    ->badge()
                    ->state(function ($record) {
                        if ($record->isSuperAdmin()) {
                            return '*';
                        }

                        return $record->permissions->count();
                    })
                    ->label(__('admin.resources.role.columns.permissions')),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }

    public static function definedPermissions(): array
    {
        return [
            'resource_role_view_any' => __('admin.resources.role.permissions.view_any'),
            'resource_role_view' => __('admin.resources.role.permissions.view'),
            'resource_role_create' => __('admin.resources.role.permissions.create'),
            'resource_role_edit' => __('admin.resources.role.permissions.edit'),
            'resource_role_delete' => __('admin.resources.role.permissions.delete'),
        ];
    }
}
