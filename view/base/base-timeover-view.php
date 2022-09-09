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
            <div class="info-left">

                <div class="element">
                    <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">СТАТУС</p>
                    <div class="text-info">
                        <p>Работа базы завершена</p>
                    </div>
                </div>
                <div class="element">
                    <p class="<?= ($colorId==\entities\Base::BASE_RED) ? 'caption-red' : 'caption-blue' ?>">ИНСТРУКЦИЯ</p>
                    <div class="text-info">
                        <p>Чтобы посмотреть ваше задание - нажмите на зеленую кнопку.</p>
                    </div>
                </div>
            </div>                
        </div>
    </main>

</body>
</html>