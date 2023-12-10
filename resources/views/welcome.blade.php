<!DOCTYPE html >
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark"
>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Collective-Base</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" integrity="sha512-wnea99uKIC3TJF7v4eKk4Y+lMz2Mklv18+r4na2Gn1abDRPPOeef95xTzdwGD9e6zXJBteMIhZ1+68QC5byJZw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/base.css" integrity="sha512-z66y0PypNStA/ynlSM3kk1/SDr1n7pIBpQO86uxkHAtBYOJJzXAAFq9rjFz8Egh25cpv3EPcrS4Y8nVHyKB9Dw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/base.min.css" integrity="sha512-TbSbl7LIRNEKzATRyVAfAPIfRmd/2lMmh3YtkMELfpGSkUJP8CDxebT1XDw9LBU1N5vK+So58Goia0XlztByug==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/components.css" integrity="sha512-9BlWpvccUKhxv9Jzha/JN6P+4hrVmhka0a0tnjtuv+Ro/V8itR/f0FM28++8CiK2CQ/qXu56VzGoHpGT3dJTKg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/components.min.css" integrity="sha512-f6TS5CFJrH/EWmarcBwG54/kW9wwejYMcw+I7fRnGf33Vv4yCvy4BecCKTti3l8e8HnUiIbxx3V3CuUYGqR1uQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind-dark.css" integrity="sha512-3woKixn3VczzzCzWvI7XqNNfVXP0fQVn4dLXg/3Uj0h/p35KnT0nkeriLR50HwrPe+agHiBMN56Q34GvHG2mag==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind-dark.min.css" integrity="sha512-5qCImO4bnvlpsqNsYuZEHlzJhEN3MBqd3GZp0QCJS0gVNq3Q+MT9Msw8c4UT2j5Cuq/29kz3jCmgYJpekNqSMQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind-experimental.css" integrity="sha512-vA/fpEI8+rrDsPceGG+Rz4NBhaNE4lvJ8CrNfspqDQi6uyIs82Hwr8gm/E+SRs+ZKjJ2ihOdb6esDSAuJrWOhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind-experimental.min.css" integrity="sha512-wnea99uKIC3TJF7v4eKk4Y+lMz2Mklv18+r4na2Gn1abDRPPOeef95xTzdwGD9e6zXJBteMIhZ1+68QC5byJZw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.css" integrity="sha512-vA/fpEI8+rrDsPceGG+Rz4NBhaNE4lvJ8CrNfspqDQi6uyIs82Hwr8gm/E+SRs+ZKjJ2ihOdb6esDSAuJrWOhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/utilities.css" integrity="sha512-ReBuhRcoSR6TYErmFMzIVea0IP68fOqzEGZO0zBaH6YtBN7+4CNc2ARxtYzQyYoPs84tYf18cQr19azyXih6rQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/utilities.min.css" integrity="sha512-Y8cJYgNbd3VacNNezxAdncUu75Uj7uR/Dtb4ffepQrtFww5/QlgYt2IwexMwB8/SZWCCVe6kOY20A2Q4zQf5vQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="max-w-7xl mx-auto p-6 lg:p-8">
                <div class="flex justify-center">
                    <img width="300" height="300" class="rounded-sm mt-4" src="{{ "/img/collective-base-logo.jpg" }}" alt="user avatar" />
                </div>

                <div class="mt-16">
                    <div class="ml-4 text-center text-sm text-gray-500 dark:text-gray-400 sm:text-right sm:ml-0">
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </div>
                </div>

                
                
            </div>
            
        </div>

       
    </body>
</html>
