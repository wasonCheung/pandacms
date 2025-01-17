<?php

declare(strict_types=1);

namespace App\Foundation\Services;

use App\Foundation\Models\User;
use Illuminate\Config\Repository;

class AvatarService
{
    public function __construct(protected Repository $config) {}

    public function getUrl(User $user): string
    {
        return "/{$this->getSymbolicLink()}/{$this->getStorageDirectory()}/{$this->getFileName($user)}";
    }

    public function getStorageDirectory(): string
    {
        return $this->config->get('pandacms.foundation.avatar.directory');
    }

    public function getFileName(User $user): string
    {
        return "{$user->name}.jpg";
    }

    public function getSymbolicLink(): string
    {
        return collect($this->config->get('filesystems.links', []))
            ->search($this->getStorageRoot());
    }

    public function getStorageRoot(): string
    {
        return $this->config->get("filesystems.disks.{$this->getStorageDisk()}.root");
    }

    public function getStorageDisk(): string
    {
        return $this->config->get('pandacms.foundation.avatar.disk');
    }
}
