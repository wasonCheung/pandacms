<?php

declare(strict_types=1);

namespace App\Admin\Panel\Resources\User;

use App\Admin\Panel\Contracts\ResourceForm;
use App\Foundation\Services\AvatarService;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Hash;

class UserForm extends ResourceForm
{
    public const REGEX_NAME = '/^[a-zA-Z0-9]+$/';

    public function __construct(protected AvatarService $avatarService) {}

    public function buildEditForm(): Form
    {
        return $this->form
            ->columns(1)
            ->schema([
                Split::make([
                    Section::make([
                        $this->avatar(),
                        $this->name()
                            ->disabled(),
                        $this->email(),
                    ]),
                    Section::make([
                        $this->password(),
                        $this->passwordConfirm(),
                    ]),
                ])->from('md'),
            ]);
    }

    public function avatar(): FileUpload
    {
        return FileUpload::make('avatar')
            ->hiddenLabel()
            ->alignCenter()
            ->imageResizeTargetWidth('150')
            ->imageResizeTargetHeight('150')
            ->circleCropper()
            ->avatar()
            ->disk($this->avatarService->getStorageDisk())
            ->directory($this->avatarService->getStorageDirectory())
            ->deleteUploadedFileUsing(function ($record) {
                $this->avatarService->delete($record);
            });
    }

    public function name(): TextInput
    {
        return TextInput::make('name')
            ->required()
            ->regex(self::REGEX_NAME)
            ->validationMessages([
                'regex' => self::getTranslation('name_regex'),
            ])
            ->maxLength(15)
            ->label(self::getTranslation('name'));
    }

    public function email(): TextInput
    {
        return TextInput::make('email')
            ->email()
            ->label(self::getTranslation('email'));
    }

    public function password(): TextInput
    {
        return TextInput::make('password')
            ->key('password')
            ->password()
            ->currentPassword()
            ->revealable()
            ->minLength(6)
            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
            ->dehydrated(fn (?string $state): bool => filled($state))
            ->label(self::getTranslation('password'));
    }

    public function passwordConfirm(): TextInput
    {
        return TextInput::make('password_confirmation')
            ->password()
            ->revealable()
            ->same('password')
            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
            ->dehydrated(fn (?string $state): bool => filled($state))
            ->validationMessages([
                'same' => self::getTranslation('password_confirmation_same'),
            ])
            ->label(self::getTranslation('password_confirmation'));
    }
}
