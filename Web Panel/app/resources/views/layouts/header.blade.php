<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','page-title')</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- [Favicon] icon -->
    <link rel="icon" href="/assets/images/xlogo.png" type="image/x-icon">
    <!-- [Font] Family -->
    <link rel="stylesheet" href="/assets/fonts/inter/inter.css" id="main-font-link" />

    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="/assets/fonts/tabler-icons.min.css" />
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="/assets/fonts/feather.css" />
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="/assets/fonts/fontawesome.css" />
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="/assets/fonts/material.css" />
    <!-- [Template CSS Files] -->
    @php $selectedLanguage = env('APP_LOCALE', 'en'); @endphp
    @if($selectedLanguage=='fa')
        <link rel="stylesheet" href="/assets/css/style-fa-ir.css?v=3" id="main-style-link" />
    @else
        <link rel="stylesheet" href="/assets/css/style-en-us.css?v=3" id="main-style-link" />
    @endif
    <link rel="stylesheet" href="/assets/css/style-preset.css" />
    <link rel="stylesheet" href="/assets/css/persian-datepicker.css"/>
    

</head>
