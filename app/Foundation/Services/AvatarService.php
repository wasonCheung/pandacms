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
    public const STORAGE_DIRECTORY = 'avatars';

    protected array $providers = [
        'ui' => UiAvatarsProvider::class,
    ];

    public function __construct(
        protected Repository $config,
        protected FilesystemManager $filesystemManager,
    ) {}

    public function getUrl(User $user): string
    {
        return $this->getFilesystem()->url($user->avatar);
    }

    public function exists(User $user): bool
    {
        if ($user->avatar) {
            return $this->getFilesystem()->exists($user->avatar);
        }

        return false;
    }

    public function getFilesystem(): Filesystem
    {
        return $this->filesystemManager->disk($this->getStorageDisk());
    }

    public function getStorageDisk(): string
    {
        return $this->config->get('pandacms.foundation.avatar.storage_disk');
    }

    public function getStorageDirectory(): string
    {
        return self::STORAGE_DIRECTORY;
    }

    public function getDefaultUrl(User $user): string
    {
        /** @noinspection PhpStrictTypeCheckingInspection */
        return app($this->getDefaultAvatarProvider())->get($user);
    }

    /**
     * @return class-string AvatarProvider
     */
    public function getDefaultAvatarProvider(): string
    {
        $default = $this->config->get('pandacms.foundation.avatar.default_provider');
        if (isset($this->providers[$default])) {
            return $this->providers[$default];
        }
        throw new InvalidArgumentException("Invalid default avatar provider [{$default}].");
    }

    public function delete(User $user): bool
    {
        if ($this->exists($user)) {
            $res = $this->getFilesystem()->delete($user->avatar);
            if ($res) {
                $user->avatar = null;

                return $user->save();
            }

            return false;
        }

        return true;
    }

    public function addDefaultProvider(string $name, string $provider): void
    {
        $this->providers[$name] = $provider;
    }
}
