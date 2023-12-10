<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css"  rel="stylesheet" />
        <!-- Scripts -->
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

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.js" integrity="sha512-x6oUSDeai/Inb/9HFvbrBtDOnLvFSd37f6j2tKRePhFBLYAezejnN5xgG52M20rnFN66+6EWwuFwAneEXyq6oA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js" integrity="sha512-AB2vAMVrtmmI+2BwSMqB+y1qGPNJovUOCp4w27S9pvX8yXPQNbBO4kuM952+LlOpng9VeWPb86b5N32bkvXRvQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/module.cjs.js" integrity="sha512-5nIN5X9Xrb8kJDTO7E0tXPaUClmR8ZXv64K/1BqQIfnilVjd3k0Y72AglJgVQtbh5MIJ4tGJ0XxQm6IlbwjgEg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/module.cjs.min.js" integrity="sha512-a4ZnWS2AJ74fCO8Qlar730zEgX1vKb/erWxLrV4BnX0NW2aZpfBJxPDOl5KOf4O+Uue5XkhKvLvqjJSWz6YY4A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/module.esm.js" integrity="sha512-tvAszahhQQe+69mcEoiu8OqnGQjiyUTpJk5cpwr9ZgJGzwH0baSqE7SlT6qBn4b+GCxdbzARY/hZPK7UctweFw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/module.esm.min.js" integrity="sha512-71h/YZHc5M+NYiENCR2P1+9zOhRRjdo9m+maWPzGU+dB7eUQ7iJsKzryvWj9gjkC6+JBrpK0Mm8NzH497Ey/nw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    </head>
    <body class="font-sans text-gray-900 antialiased">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <div>
                <a href="/">
                    <div class="flex justify-center">
                        <img width="100" height="100" class="rounded-sm mt-4" src="{{ "/img/collective-base-logo.jpg" }}" alt="user avatar" />
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
