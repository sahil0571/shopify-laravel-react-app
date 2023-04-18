<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Accessibility') }}</title>

    <script>
        window.currentUser = "{{ $user }}"
        window.plan_id = "{{ $plan }}"
    </script>
</head>

<body>

    <div id="app"></div>
    @viteReactRefresh
    @vite('resources/js/app.js')

</body>

</html>
