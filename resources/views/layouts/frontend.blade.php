<head>
    <style>
        {!! $appPreferences['custom_css_head'] !!}
    </style>
    {!! $appPreferences['custom_code_head'] !!}
</head>
<body>
@yield('content')
{!! $appPreferences['custom_code_body'] !!}
</body>
