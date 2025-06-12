<x-mail::message>
    Добро пожаловать, {{ $name }}!

    Спасибо за регистрацию в {{ config('app.name') }}.

    <x-mail::button :url="env('APP_URL')">
    Перейти на сайт
    </x-mail::button>

    С уважением, {{ config('app.name') }}
</x-mail::message>
