@props(['url'])
<tr>
<td class="header">
<a href="{{ "https://classscanmx.site/login" }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://classscanmx.site/images/logo_final.png" alt="Logotipo" style="max-width: 150px; height: auto; margin-bottom: 20px;">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>

