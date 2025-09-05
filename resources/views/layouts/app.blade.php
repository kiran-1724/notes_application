<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Notes') }}</title>
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
              <script src="https://cdn.tailwindcss.com"></script>
    
        <!-- Alpine.js for dropdowns -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <script>
            // Tailwind config to match your design system
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            'inter': ['Inter', 'sans-serif'],
                        },
                        colors: {
                            border: "hsl(214.3 31.8% 91.4%)",
                            input: "hsl(214.3 31.8% 91.4%)",
                            ring: "hsl(222.2 84% 4.9%)",
                            background: "hsl(0 0% 100%)",
                            foreground: "hsl(222.2 84% 4.9%)",
                            primary: {
                                DEFAULT: "hsl(222.2 47.4% 11.2%)",
                                foreground: "hsl(210 40% 98%)",
                            },
                            secondary: {
                                DEFAULT: "hsl(210 40% 96%)",
                                foreground: "hsl(222.2 84% 4.9%)",
                            },
                            muted: {
                                DEFAULT: "hsl(210 40% 96%)",
                                foreground: "hsl(215.4 16.3% 46.9%)",
                            },
                            accent: {
                                DEFAULT: "hsl(210 40% 96%)",
                                foreground: "hsl(222.2 84% 4.9%)",
                            },
                        },
                    }
                }
            }
        </script>
    </head>
    <body class="font-inter bg-gray-50 text-foreground antialiased">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white border-b border-border/40">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>