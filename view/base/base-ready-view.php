<?php
$colorId = $base->getBaseColorId($round['MissionId']);
?>
<!DOCTYPE html>
<html lang="ru" style=" overflow:  hidden;">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/assets/css/style-blue-red.css">
    <link rel="stylesheet" href="/assets/css/style-statistic.css">

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
             
                <?php if($base->isReady()): ?>
                    <div class="element">
                        <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">СТАТУС</p>
                        <div class="text-info">
                            <p class="margin-bottom">Команда <?= ($colorId==\entities\Base::BASE_RED) ? 'красных' : 'синих' ?> готова.
                                <br>
                                Ожидаем готовности <?= ($colorId==\entities\Base::BASE_RED) ? 'синей' : 'красной' ?> команды.
                            </p>
                        </div>
                    </div>
                    <?php else: ?>
                        <div class="element">
                            <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">СТАТУС</p>
                            <div class="text-info">
                                <p class="margin-bottom">Ожидание готовности
                                    <br>
                                </p>
                            </div>
                        </div>
                        <div class="element">
                            <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">ИНСТРУКЦИЯ</p>
                            <div class="text-info">
                                <p class="margin-bottom">Ваша команда готова к выполнению задания? Тогда нажмите и удерживайте кнопку под монитором для старта миссии.
                                    <br>
                                    <br>
                                    Чтобы посмотреть задание, нажмите на зеленую кнопку.
                                </p>
                            </div>
                        </div>
                    <?php endif ?>

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
