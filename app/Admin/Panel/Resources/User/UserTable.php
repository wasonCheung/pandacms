<?php

declare(strict_types=1);

namespace App\Admin\Panel\Resources\User;

use App\Admin\Panel\Contracts\ResourceTable;
use App\Foundation\Enums\DefaultGuard;
use App\Foundation\Services\AvatarService;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class UserTable extends ResourceTable
{
    public function __construct(protected AvatarService $avatarService) {}

    public function editAction()
    {
        return EditAction::make();
    }

    public function getColumns()
    {
        return Split::make([
            ImageColumn::make('avatar')
                ->state(fn ($record) => $this->avatarService->url($record))
                ->defaultImageUrl(fn ($record) => $this->avatarService->defaultUrl($record))
                ->circular()
                ->grow(false),
            TextColumn::make('name')
                ->copyable()
                ->description(fn ($record) => $record?->display_name)
                ->searchable(),
            Stack::make([
                TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),
                TextColumn::make('email_verified_at')
                    ->icon('heroicon-o-check-circle')
                    ->iconColor('success')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->iconColor(fn ($record) => $record->updated_at->isToday() ? 'success' : null)
                    ->icon('heroicon-m-pencil-square'),
            ]),
        ])->from('sm');
    }

    public function rolesFilter()
    {
        return SelectFilter::make('roles')
            ->label((string) __class(__CLASS__, 'roles_filter'))
            ->relationship('roles', 'name')
            ->getOptionLabelFromRecordUsing(function ($record) {
                $guardLabel = DefaultGuard::tryFrom($record->guard_name)->getLabel();

                return "[$guardLabel]".__model($record, 'name');
            });
    }

    public function guardFilter()
    {
        return SelectFilter::make('guard')
            ->label((string) __class(__CLASS__, 'guard_filter'))
            ->options(DefaultGuard::class)
            ->query(function ($query, $state) {
                $value = $state['value'];
                if (empty($value)) {
                    return $query;
                }

                return $query->whereHas('roles', function ($q) use ($value) {
                    $q->where('guard_name', $value);
                });
            });
    }
}
