<?php
use classes\BaseHelper;
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


            <?php if($base->isEndlessRevival()): ?>

                <div class="info-left">
                    <div class="element">
                        <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">РЕЖИМ РАБОТЫ</p>
                        <div class="text-info">
                            <p class="margin-bottom">Бесконечное возрождение
                            </p>
                        </div>
                    </div>
                    <div class="element">
                        <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">СТАТУС</p>
                        <div class="text-info">
                            <p class="margin-bottom"><?= BaseHelper::calculateToTimeOver(strtotime($round['StartDate']), $missionInfo['TimeWorkBase'.$colorName]) ?></p>
                        </div>
                    </div>

                    <div class="element">
                        <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">ИНСТРУКЦИЯ</p>
                        <div class="text-info">
                            <p class="margin-bottom">
                                Если вас убили нажмите и удерживайте красную кнопку.
                                <br>Когда весь индикатор над монитором заполнится - вы возродитесь.
                                <br>
                                <br>
                                Чтобы просмотреть ваша задание - нажмите зеленую кнопку.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="team-name">
                    <p class="city">ГОРОД</p>
                    <p>ЗЛОДЕЕВ</p>
                </div>
                <div class="resources">
                    <div class="element-1">
                        <p class="margin-bottom">Возрождения</p>
                        <img src="/assets/images/medicines.png">
                    </div>
                </div>
            <?php endif ?>

            <?php if($base->isRevivalByTime()): ?>
                <div class="info-left">
                    <div class="element">
                        <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">РЕЖИМ РАБОТЫ</p>
                        <div class="text-info">
                            <p class="margin-bottom">Возрождение по времени
                            </p>
                        </div>
                    </div>
                    <div class="element">
                        <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">СТАТУС</p>
                        <div class="text-info">
                            <p class="margin-bottom"><?= BaseHelper::calculateToTimeOver(strtotime($round['StartDate']), $missionInfo['TimeWorkBase'.$colorName]) ?></p>
                        </div>
                    </div>
                    <div class="element">
                        <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">ИНСТРУКЦИЯ</p>
                        <div class="text-info">
                            <p class="margin-bottom">
                                Если вас убили - ождайте возле базы, база сама возродит вас.
                                <br>
                                <br>
                                Чтобы просмотреть ваша задание - нажмите зеленую кнопку.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="team-name">
                    <p class="city">ГОРОД</p>
                    <p>ЗЛОДЕЕВ</p>
                </div>
            <?php endif ?>

            <?php if($base->isLimitedRevival()): ?>
                <div class="info-left">
                    <div class="element">
                        <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">РЕЖИМ РАБОТЫ</p>
                        <div class="text-info">
                            <p class="margin-bottom">Ограниченное возрождение
                            </p>
                        </div>
                    </div>
                    <div class="element">
                        <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">СТАТУС</p>
                        <div class="text-info">
                            <p class="margin-bottom"><?= BaseHelper::calculateToTimeOver(strtotime($round['StartDate']), $missionInfo['TimeWorkBase'.$colorName]) ?></p>
                        </div>
                    </div>

                    <div class="element">
                        <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">ИНСТРУКЦИЯ</p>
                        <div class="text-info">
                            <p class="margin-bottom">
                                Если вас убили нажмите и удерживайте красную кнопку.
                                Когда весь индикатор над монитором заполнится - вы возродитесь.
                                <br><br>
                                Количество возрождений переодически возобновляются.
                                <br><br>
                                Чтобы просмотреть ваша задание - нажмите зеленую кнопку.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="team-name">
                    <p class="city">ГОРОД</p>
                    <p>ЗЛОДЕЕВ</p>
                </div>
                <div class="resources">
                    <div class="element-1">
                        <p class="margin-bottom">Возрождения</p>
                        <img src="/assets/images/medicines.png">
                        <p class="counter"><?= BaseHelper::getAdditionalDiz($game, $mission, $base, $base->getType()) ?></p>
                    </div>
                </div>

            <?php endif ?>

            <?php if($base->isHealing()): ?>

                <div class="info-left">
                    <div class="element">
                        <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">РЕЖИМ РАБОТЫ</p>
                        <div class="text-info">
                            <p class="margin-bottom">Лечение
                            </p>
                        </div>
                    </div>
                    <div class="element">
                        <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">СТАТУС</p>
                        <div class="text-info">
                            <p class="margin-bottom"><?= BaseHelper::calculateToTimeOver(strtotime($round['StartDate']), $missionInfo['TimeWorkBase'.$colorName]) ?></p>
                        </div>
                    </div>

                    <div class="element">
                        <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">ИНСТРУКЦИЯ</p>
                        <div class="text-info">
                            <p class="margin-bottom">
                                Если вас ранили нажмите и удерживайте красную кнопку.
                                <br>
                                Когда весь индикатор над монитором заполнится - вы получите 100% здоровья.
                                <br>
                                <br>
                                ВНИМАНИЕ!!! Если вас убили, вы не сможете восполнить здоровье, 
                                <br>
                                база лечит только раненых игроков.
                                <br>
                                <br>
                                Чтобы просмотреть ваша задание - нажмите зеленую кнопку.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="team-name">
                    <p class="city">ГОРОД</p>
                    <p>ЗЛОДЕЕВ</p>
                </div>
                <div class="resources">
                    <div class="element-1">
                        <p class="margin-bottom">Возрождения</p>
                        <img src="/assets/images/medicines.png">
                    </div>
                </div>

            <?php endif ?>

            <?php if($base->isStock()): ?>
                <?php
                $resources = $base->getResources();
                ?>



                <div class="info-left">
                    <div class="element">
                        <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">РЕЖИМ РАБОТЫ</p>
                        <div class="text-info">
                            <p class="margin-bottom">Склад
                            </p>
                        </div>
                    </div>
                    <div class="element">
                        <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">СТАТУС</p>
                        <div class="text-info">
                            <p class="margin-bottom"><?= BaseHelper::calculateToTimeOver(strtotime($round['StartDate']), $missionInfo['TimeWorkBase'.$colorName]) ?></p>
                        </div>
                    </div>

                    <div class="element">
                        <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">ИНСТРУКЦИЯ</p>
                        <div class="text-info">
                            <p class="margin-bottom">
                                Захватывайте точки на поле боя, зарабатывайте ресурсы. 
                                <br>
                                Чтобы активировать ресурс - нажмите и удерживайте кнопку нужного цвета.
                                <br>
                                <br>
                                Чтобы просмотреть ваша задание - нажмите зеленую кнопку.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="team-name">
                    <p class="city">ГОРОД</p>
                    <p>ЗЛОДЕЕВ</p>
                </div>
                <div class="resources">

                    <div class="element-1">
                        <p class="margin-bottom">Возрождения</p>
                        <img src="/assets/images/medicines.png">
                        <p class="counter"><?= $resources->getRessurections() ?></p>
                    </div>

                    <div class="element-2">
                        <p class="margin-bottom">Патроны</p>
                        <img src="/assets/images/cartridges.png">
                        <p class="counter"><?= $resources->getAmmo() ?></p>
                    </div>

                    <div class="element-3">
                        <p class="margin-bottom">Ресурсы</p>
                        <img src="/assets/images/resources.png">
                        <p class="counter"><?= count($resources->getAchivments()) ?></p>
                    </div>

                </div>
            <?php endif ?>
        </div>

    </main>

</body>
</html>