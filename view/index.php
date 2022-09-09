<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link type="text/css" href="/assets/css/reset.css" rel="stylesheet">
    <link type="text/css" href="/assets/css/global.css" rel="stylesheet">
    <link type="text/css" href="/assets/css/grid.css" rel="stylesheet">
    <link type="text/css" href="/assets/css/main.css" rel="stylesheet">
    <script src="/assets/js/jquery-2.1.3.min.js"></script>
    <script src="/assets/js/interface.js"></script>
    <script src="/assets/js/common.js"></script>
    <title>Панель оператора</title>
</head>
<body class="b-body">
<header class="b-header">
    <div class="b-logo"><img src="/assets/images/logo.png"></div>

    <div class="g-clearfix b-head-menu">
        <ul>
            <li><a href="/admin/games.html">Игры</a></li>
            <li><a href="/admin/missions.html">Сценарии</a></li>
            <li><a href="/admin/sounds.html">Пресеты звуков</a></li>
            <li><a href="/admin/settings.html">Настройки</a></li>
        </ul>
    </div>
</header>


<main class="b-main">
    <?php require($_SERVER['DOCUMENT_ROOT'] . '/view/mission_'.$mission->pointsType().'/'.$view.'.php');?>
</main>

</body>
</html>