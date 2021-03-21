<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ localization()->getCurrentLocaleDirection() }}"
>
<head>
    <title>@yield('title', null)</title>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="Backoffice Panel - MultiCaret">
    <style>
        {!! $appPreferences['custom_css_head'] !!}
    </style>
    {!! $appPreferences['custom_code_head'] !!}
</head>
<body>
@yield('content')
{!! $appPreferences['custom_code_body'] !!}
</body>

</html>
