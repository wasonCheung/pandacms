<?php

declare(strict_types=1);

namespace App\Foundation\Services;

use App\Foundation\Models\User;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;

class AvatarService
{
    public function __construct(
        protected Repository $config,
        protected FilesystemManager $filesystemManager,
    ) {}

    public function getUrl(User $user): string
    {

        return $this->getFilesystem()->url($this->getStoragePath($user));
    }

    public function getFilesystem(): Filesystem
    {
        return $this->filesystemManager->disk($this->getStorageDisk());
    }

    public function getStorageDisk(): string
    {
        return $this->config->get('pandacms.foundation.avatar.storage.disk');
    }

    public function getStoragePath(User $user): string
    {
        return "{$this->getStorageDirectory()}/{$this->storageName($user)}";
    }

    public function getStorageDirectory(): string
    {
        return $this->config->get('pandacms.foundation.avatar.storage.directory');
    }

    public function storageName(User $user): string
    {
        return "{$user->name}.jpg";
    }
}
