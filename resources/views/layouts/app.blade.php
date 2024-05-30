<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

        <link rel="stylesheet" href="{{ asset('cropper/cropper.min.css') }}">
        <script src="{{ asset('cropper/cropper.min.js') }}" defer></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans antialiased">

        {{-- The navbar with `sticky` and `full-width` --}}
        <x-nav sticky full-width>

            <x-slot:brand>
                {{-- Drawer toggle for "main-drawer" --}}
                <label for="main-drawer" class="lg:hidden mr-3">
                    <x-icon name="o-bars-3" class="cursor-pointer" />
                </label>

                {{-- Brand --}}
                <div>{{ config('app.name') }}</div>
            </x-slot:brand>

            {{-- Right side actions --}}
            <x-slot:actions>
                <x-button label="Messages" icon="o-envelope" link="###" class="btn-ghost btn-sm" responsive />
                <x-button label="Notifications" icon="o-bell" link="###" class="btn-ghost btn-sm" responsive />
            </x-slot:actions>
        </x-nav>

        {{-- The main content with `full-width` --}}
        <x-main with-nav full-width>

            {{-- This is a sidebar that works also as a drawer on small screens --}}
            {{-- Notice the `main-drawer` reference here --}}
            <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-200">

                {{-- Activates the menu item when a route matches the `link` property --}}
                <x-menu activate-by-route>

                    {{-- User --}}
                    @if ($user = auth()->user())
                        <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover
                            class="-mx-2 !-my-2 rounded">
                            <x-slot:actions>
                                <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="logoff"
                                    no-wire-navigate link="/logout" />
                            </x-slot:actions>
                        </x-list-item>

                        <x-menu-separator />
                    @endif

                    {{-- Menu items --}}
                    <x-menu-item title="Home" icon="o-sparkles" link="/dashboard" />
                    <x-menu-item title="Users" icon="o-users" link="/users" />
                    <x-menu-sub title="Settings" icon="o-cog">
                        <x-menu-item title="Wifi" icon="o-wifi" link="####" />
                        <x-menu-item title="Archives" icon="o-archive-box" link="####" />
                    </x-menu-sub>
                </x-menu>
            </x-slot:sidebar>

            {{-- The `$slot` goes here --}}
            <x-slot:content>
                {{ $slot }}
            </x-slot:content>
        </x-main>

        {{--  TOAST area --}}
        <x-toast />
        <x-spotlight />
    </body>

</html>
