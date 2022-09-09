<?php
$colorId = $base->getBaseColorId($round['MissionId']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/assets/css/style-blue-red.css">

    <script src="/assets/js/jquery-2.1.3.min.js"></script>
    <script src="/assets/js/base.js"></script>
    <script src="/assets/js/common.js"></script>
    <title>База</title>
</head>

<body>
    <main class="b-main">
        <div class="<?= ($colorId==\entities\Base::BASE_RED) ? 'background-red' : 'background-blue' ?>">

            <!--блок "СТАТУС, ИНСТРУКЦИЯ, ЗАДАНИЕ"-->
            <div class="info-left">
                <div class="element">
                    <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">НАЗВАНИЕ МИССИИ</p>
                    <div class="text-info">
                        <p class="margin-bottom">Название мисии
                        </p>
                    </div>
                </div>

                <div class="element">
                    <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">ВАШЕ ЗАДАНИЕ</p>
                    <div class="text-info">
                        <p class="margin-bottom">Тут будет размешено задание к мисии.
                        </p>
                    </div>
                </div>

                <div class="element">
                    <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">ИНСТРУКЦИЯ</p>
                    <div class="text-info">
                        <p class="margin-bottom">Нажмите на зеленую кнопку чтобы открыть дальнейшие инструкции.
                            <br>
                        </p>
                    </div>
                </div>
                

            </div>
            <!--конец блока "СТАТУС, ИНСТРУКЦИЯ, ЗАДАНИЕ"-->

            <!--блок названия команды-->
            <div class="team-name">
                <p class="city">ГОРОД</p>
                <p>ЗЛОДЕЕВ</p>
            </div>
            <!--конец блока названия команды-->
        </div>
    </div>
</main>
</body>
</html>
