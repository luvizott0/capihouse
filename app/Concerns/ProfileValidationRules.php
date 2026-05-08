<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

trait ProfileValidationRules
{
    /**
     * Get the validation rules used to validate user profiles.
     *
     * @return array<string, array<int, ValidationRule|array<mixed>|string>>
     */
    protected function profileRules(?int $userId = null): array
    {
        return [
            'name' => $this->nameRules(),
            'username' => $this->usernameRules($userId),
            'email' => $this->emailRules($userId),
            'avatar_url' => $this->avatarUrlRules(),
            'banner_url' => $this->bannerUrlRules(),
            'bio' => $this->bioRules(),
            'birth' => $this->birthRules(),
        ];
    }

    /**
     * Get the validation rules used to validate user names.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function nameRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    /**
     * Get the validation rules used to validate usernames.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function usernameRules(?int $userId = null): array
    {
        return [
            'required',
            'string',
            'max:50',
            'regex:/^[A-Za-z0-9._]+$/',
            $userId === null
                ? Rule::unique(User::class)
                : Rule::unique(User::class)->ignore($userId),
        ];
    }

    /**
     * Get the validation rules used to validate user emails.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function emailRules(?int $userId = null): array
    {
        return [
            'required',
            'string',
            'email',
            'max:255',
            $userId === null
                ? Rule::unique(User::class)
                : Rule::unique(User::class)->ignore($userId),
        ];
    }

    /**
     * Get the validation rules used to validate profile avatar URL.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function avatarUrlRules(): array
    {
        return ['nullable', 'string', 'url', 'max:2048'];
    }

    /**
     * Get the validation rules used to validate profile banner URL.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function bannerUrlRules(): array
    {
        return ['nullable', 'string', 'url', 'max:2048'];
    }

    /**
     * Get the validation rules used to validate profile bio.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function bioRules(): array
    {
        return ['nullable', 'string', 'max:255'];
    }

    /**
     * Get the validation rules used to validate profile birthday.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function birthRules(): array
    {
        return ['nullable', 'date', 'before_or_equal:today'];
    }
}
