<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SL_GRADES</title>
</head>
<body>
    <h1>Noten</h1>

    <ul>
        @foreach ($courses as $course)
            <li>{{ $course->module_nr }}</li>
        @endforeach
    </ul>
</body>
</html>
