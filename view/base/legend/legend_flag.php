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
                        <p class="margin-bottom">Перенос флага
                        </p>
                    </div>
                </div>

                <div class="element">
                    <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">ВАШЕ ЗАДАНИЕ</p>
                    <div class="text-info">
                        <p class="margin-bottom">
                            Захватить флаг противника и доставить его на свою базу, пройдя определенное количество точек за определенное время.
                        </p>
                    </div>
                </div>

                <div class="element">
                    <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">ОСОБЕННОСТИ</p>
                    <div class="text-info">
                        <p class="margin-bottom">
                            Захват производится одним игроком, этот игрок должен пройти определенные точки на поле за определенное время, добежать до базы и захватить финальную. 
                            <br>
                            Если игрок не успел за определенное время флаг сбрасывается и появляется на исходном месте. 
                            <br>
                            Если игрок несущий флаг погибает – флаг появляется на исходном месте лишь спустя определенное время.

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
