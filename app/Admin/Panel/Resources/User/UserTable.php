<?php
declare(strict_types=1);

namespace App\Admin\Panel\Resources\User;

use App\Admin\Panel\Contracts\ResourceTable;
use Filament\Tables\Columns\TextColumn;

class UserTable extends ResourceTable
{
    public function nameColumn()
    {
        return TextColumn::make('name')
            ->label(__('admin/resources/user.table.name'))
            ->searchable();
    }
}
