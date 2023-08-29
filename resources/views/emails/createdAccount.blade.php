<x-mail::message>
# User Credentials for Connexion

Dear Customer, Welcome to the online restaurant platform {{ config('app.name')}}

Please find below your access credentials to our platform.

<div><strong>Username:</strong> {{$account->CTE_PHONE}} or {{$account->CTE_EMAIL}}</div>
<div><strong>Password:</strong> {{$password}}</div>

We invite you to change your password after your first login.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
