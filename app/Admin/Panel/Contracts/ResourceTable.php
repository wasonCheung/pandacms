<?php

declare(strict_types=1);

namespace App\Admin\Panel\Contracts;

use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

abstract class ResourceTable
{
    protected readonly Table $table;

    public static function make(Table $table): Table
    {
        return app(static::class)
            ->table($table)
            ->buildTable();
    }

    public function buildTable(): Table
    {
        return self::tableDefaultUsingConfig($this->table)
            ->filters($this->filtersDiscover())
            ->actions($this->actionsDiscover())
            ->columns($this->columnsDiscover());
    }

    public static function tableDefaultUsingConfig(Table $table): Table
    {
        return $table
            ->contentGrid(['md' => 2])
            ->filtersLayout(FiltersLayout::Dropdown);
    }

    private function filtersDiscover(): array
    {
        $filters = [];
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if (str_ends_with($method, 'Filter')) {
                $filters[] = $this->$method();
            }
        }

        return $filters;
    }

    private function actionsDiscover(): array
    {
        $actions = [];
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if (str_ends_with($method, 'Action')) {
                $actions[] = $this->$method();
            }
        }

        return $actions;
    }

    private function columnsDiscover(): array
    {
        $columns = [];
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if (str_ends_with($method, 'Column') || str_ends_with($method, 'Columns')) {
                $columns[] = $this->$method();
            }
        }

        return $columns;
    }

    public function table(Table $table): static
    {
        $this->table = $table;

        return $this;
    }
}
