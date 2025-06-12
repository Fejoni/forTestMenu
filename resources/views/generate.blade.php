<!DOCTYPE html>
<html >
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Генерация меню</title>


</head>
<body>

<form method="POST" action="{{ route('generate-menu') }}">
    @csrf
    <button>JSON меню</button><br><br>
    <textarea name="text" id="" cols="120" rows="40"></textarea>

</form>
</body>
</html>
