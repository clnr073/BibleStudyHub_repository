<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Blog</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <h1>Reading</h1>
        <div class='testaments'>
            @foreach($testaments as $testament)
                <div class='testament'>
                    <h2 class='title'>{{ $testament->volume->title }}</h2>
                    <h3 class='chapter'>第{{ $testament->chapter }}章</h3>
                    <p>{{ $testament->text }}</p>
                </div>
            @endforeach
         </div>
    </body>
</html>