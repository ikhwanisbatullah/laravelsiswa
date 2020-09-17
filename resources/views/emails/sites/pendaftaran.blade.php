@component('mail::message')
# Pedafaran

Selamat.

@component('mail::button', ['url' => 'http://rolloic.com'])
Klik disini
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
