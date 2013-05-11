<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
</head>
<body>

    Hey Welcome to {{ Config::get('app.url', 'Awesome Webapp'); }}
    to activate your account please click on following link
    {{ Html::link($url, $url) }}
</body>
</html>