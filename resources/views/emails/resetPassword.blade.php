@component('mail::message')
# Password Reset

Click on the button below to reset your password 👇
{{-- , ['url' => route('verificationEmailForRegistration', ['token' => $verificationToken])] --}}
@component('mail::button', ['url' => route('reset_password', ['token' => $token])])
Reset my password
@endcomponent

If you think this request looks suspicious, please let us know we take our security seriously.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
