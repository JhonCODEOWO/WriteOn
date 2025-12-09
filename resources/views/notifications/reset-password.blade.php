<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1> Has solicitado restablecer tu contraseña </h1>
    <h3> Si no has sido tú simplemente ignora este correo</h3>
    <p>
        Si has sido tú entonces pulsa <a href="{{$urlFrontend}}?email={{$email}}&token={{$token}}">aquí</a> para restablecerla.
    </p>

    <h5>El link en este correo será válido hasta las {{$timeLimit}}</h5>
</body>
</html>