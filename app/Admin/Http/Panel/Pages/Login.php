<?php

declare(strict_types=1);

namespace App\Admin\Http\Panel\Pages;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\SimplePage;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Session;

/**
 * @property Form $form
 */
class Login extends SimplePage
{
    use InteractsWithFormActions;
    use WithRateLimiting;

    protected static string $view = 'filament-panels::pages.auth.login';

    public ?array $data = [];

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }
        $this->form->fill();
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $e) {
            throw new LoginLimitAdminException($e);
        }

        $data = $this->form->getState();

        $credentials = [
            'name' => $data['name'],
            'password' => $data['password'],
        ];

        $result = Filament::auth()->attempt($credentials, $data['remember'] ?? false);

        if (false === $result) {
            throw new LoginInvalidAdminException();
        }

        Session::regenerate();

        return app(LoginResponse::class);
    }

    public function getTitle(): string|Htmlable
    {
        return __('admin.pages.login.title');
    }

    public function getFormActions(): array
    {
        return [
            Action::make('doLogin')
                ->label(__('admin.pages.login.form.do_login'))
                ->submit('doLogin'),
        ];
    }

    protected function getForms(): array
    {
        $remember = Checkbox::make('remember')
            ->label(__('admin.pages.login.form.remember'));

        $password = TextInput::make('password')
            ->label(__('admin.pages.login.form.password'))
            ->password()
            ->minLength(4)
            ->maxLength(30)
            ->revealable(filament()->arePasswordsRevealable())
            ->autocomplete('current-password')
            ->required()
            ->extraInputAttributes(['tabindex' => 2]);

        $name = TextInput::make('name')
            ->label(__('admin.pages.login.form.name'))
            ->required()
            ->autocomplete()
            ->autofocus()
            ->minLength(4)
            ->maxLength(30)
            ->extraInputAttributes(['tabindex' => 1]);

        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $name, $password, $remember,
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    public function form(Form $form): Form
    {
        return $form;
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }
}
