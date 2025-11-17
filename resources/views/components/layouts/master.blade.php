<!DOCTYPE html>

<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="layout-wide customizer-hide"
    dir="ltr"
    data-skin="default"
    data-bs-theme="light"
    data-assets-path="../../assets/"
    data-template="vertical-menu-template"
>
    <head>
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        />
        <meta name="robots" content="noindex, nofollow" />
        <title>
            {{ config("app.name") }} {{ isset($title) ? " - ".$title : "" }}
        </title>

        <meta name="description" content="" />
        @include('components.includes.styles')
    </head>

    <body>
        <!-- Content -->

        {{ $slot }}
        @include('components.includes.scripts')
    </body>
</html>
