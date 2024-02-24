@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{ Vite::asset('resources/images/brand/everestserver-logo.png') }}" alt="{{ Lang::get('home.logo-alt') }}" style="width: 64px;">
<div>{{ $slot }}</div>
</a>
</td>
</tr>
