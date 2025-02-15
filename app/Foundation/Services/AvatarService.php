<?php

declare(strict_types=1);

namespace App\Foundation\Services;

use App\Foundation\Models\User;
use Filament\AvatarProviders\UiAvatarsProvider;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use InvalidArgumentException;

class AvatarService
{
    protected array $providers = [
        'ui' => UiAvatarsProvider::class,
    ];

    public function __construct(
        protected Repository $config,
        protected FilesystemManager $filesystemManager,
    ) {}

    public function url(User $user): ?string
    {
        if ($this->exists($user) === false) {
            return null;
        }
        return $this->getFilesystem()->url($this->storagePath($user));
    }

    public function exists(User $user): bool
    {
        return $this->getFilesystem()->exists($this->storagePath($user));
    }

    public function getFilesystem(): Filesystem
    {
        return $this->filesystemManager->disk($this->getStorageDisk());
    }

    public function getStorageDisk(): string
    {
        return $this->config->get('pandacms.foundation.avatar.storage.disk');
    }

    public function storagePath(User $user): string
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

    public function defaultUrl(User $user): string
    {
        /** @noinspection PhpStrictTypeCheckingInspection */
        return app($this->getDefaultAvatarProvider())->get($user);
    }

    /**
     * @return class-string AvatarProvider
     */
    public function getDefaultAvatarProvider(): string
    {
        $default = $this->config->get('pandacms.foundation.avatar.default');
        if (isset($this->providers[$default])) {
            return $this->providers[$default];
        }
        throw new InvalidArgumentException("Invalid default avatar provider [{$default}].");
    }

    public function delete(User $user): bool
    {
        return $this->getFilesystem()->delete($this->storagePath($user));
    }

    public function defaultAvatarProvider(string $name, string $provider): static
    {
        $this->providers[$name] = $provider;

        return $this;
    }


}
