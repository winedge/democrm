<x-auth::layouts.auth>
    @section('title', __('passwords.reset_password'))

    <auth-password-reset email="{{ $email ?? null }}" token="{{ $token }}"></auth-password-reset>
</x-auth::layouts.auth>
