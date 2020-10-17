<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <script src="{{ mix('js/app.js') }}"defer></script>

        <title>googlemap</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    </head>
    <body style="width: 1440px; height: auto; margin: 0;">

        <div id="app">
            <example-component></example-component>
        </div>
  
    </body>
</html>
