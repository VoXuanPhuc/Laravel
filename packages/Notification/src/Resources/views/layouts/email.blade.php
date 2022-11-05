<x-mail::message>
    @yield('content')
    <br>
    <x-mail::subcopy>
        {{__('This is a system generated message. Please do not respond to this email.
            To ensure reliable delivery of all emails from our system, please add noreply@encoda.com to your address book.')}}
    </x-mail::subcopy>
</x-mail::message>
