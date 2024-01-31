<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
{{ config('app.name') }}
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{{ $subcopy }}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
<p>
{{ Lang::get('main.footer.programmed-with') }}
<span style="color: #dc2626;">❤</span>
{{ Lang::get('main.footer.by') }}
<a href="https://jonaszkadziela.pl" style="color: #1d4ed8; text-decoration: none;">
{{ Lang::get('main.footer.author') }}
</a>
</p>
<p style="margin-bottom: 0;">
{{ config('app.name') }} © {{ date('Y') }}. @lang('notifications.all-rights-reserved').
</p>
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
