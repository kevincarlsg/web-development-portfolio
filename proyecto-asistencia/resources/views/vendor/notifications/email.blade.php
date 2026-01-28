<x-mail::message>
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Hola!')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
<p>Recibimos una solicitud para restablecer la contraseña de tu cuenta.</p>

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
<P>Nueva contraseña</P>
</x-mail::button>
@endisset

{{-- Outro Lines --}}
<p>Este enlace de restablecimiento de contraseña expirará en 60 minutos.</p>
<p>Si no solicitaste un restablecimiento de contraseña, puedes ignorar este mensaje.</p>


{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Saludos,')<br>
<p>HASH</p>
@endif

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
@lang(
    "Si tiene problemas para hacer clic en el boton \":actionText\", copie y pegue la siguiente URL\n".
    'en su navegador:',
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
