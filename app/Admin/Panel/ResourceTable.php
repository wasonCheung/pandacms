<?php

declare(strict_types=1);

namespace App\Admin\Panel;

use Filament\Tables\Table;

abstract class ResourceTable
{
    public function __construct(public Table $table) {}

    public static function make(Table $table): Table
    {
        return app(static::class, ['table' => $table])->buildTable();
    }

    public function buildTable(): Table
    {
        return $this->table;
    }
}
