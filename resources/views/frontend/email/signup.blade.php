<!DOCTYPE html>
<html lang="en-US">
    <head>
    <meta charset="utf-8">
    </head>
    <body>
    <h2>{{ $title }}</h2>
    {{-- use nl2br to break line (PHP_EOL) in emai lbody --}}
    <p>{!! nl2br($body_line_1) !!}</p>
    <p><a href='{{ $signup_url }}'>アカウント情報の入力</a></p>
    <p>{!! nl2br($body_line_2) !!}</p>
    <p><a href='{{ $contact_us_url }}'>お問い合わせ</a></p>
    <p>{{ $body_line_3 }}</p>
    </body>
</html>
