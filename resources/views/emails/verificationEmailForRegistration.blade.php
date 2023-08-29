@component('mail::message')
# Verify Email for Registration
Welcome {{$compte->CTE_FIRSTNAME}} {{$compte->CTE_LASTNAME}}  to {{ config('app.name') }} Web Plateform.

Click on the button below to verify your email 👇

@component('mail::button', ['url' => route('verificationEmailForRegistration', ['token' => $verificationToken])])
Verify Your Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
