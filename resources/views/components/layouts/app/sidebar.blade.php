<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white ">
    @include('partials.side')
    @include('partials.nav')




    <main class="pt-20 px-4 lg:ml-64 bg-gradient-to-br from-pink-100 to-primary-200 min-h-screen">
        {{ $slot }}
    </main>

</body>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
@stack('scripts')
{{-- @fluxScripts --}}

</html>