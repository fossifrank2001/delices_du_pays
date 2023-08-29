@component('mail::message')
    # Activation Account
    Click on the button below 👇 to activate the new user's account or do it from the dashboard panel.

    @component('mail::button', ['url' => route('activate-new-account', ['account' => $account->CTE_ID_COMPTE, 'fromEmailIbox'=> 1])])
        Activate the account
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
