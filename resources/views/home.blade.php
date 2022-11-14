<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
    </head>
    <body>
        <h2 style="padding: 20px 40px; background-color:cyan;border-left: 8px solid red;">Routes Lists:</h2>
        <ol>
            <li>Login: POST domain/api/login</li>
            <li>Register: POST domain/api/login</li>
            <p>Allowed only to Students</p>
            <li>pre-reg info: GET domain/api/pre-reg</li>
            <li>pre-reg: post domain/api/pre-reg</li>
        </ol>
    </body> 
</html>
