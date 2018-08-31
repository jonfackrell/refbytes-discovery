<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    @stack('styles')
</head>
<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    {{--<script src="/js/jquery.slim.min.js"></script>--}}
    @stack('scripts')
</body>
</html>
