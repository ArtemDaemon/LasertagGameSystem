<?php
use classes\BaseHelper;
?>
<div class="b-h1">Игра <?= $game->name() ?></div>

<div class="b-game__box">
    <div class="b-h2">Ожидание готовности игроков</div>
    <div class="js-reload__team-ready">
        <?php if($readyBases): ?>
            <div class="g-m-t5">
                <?php foreach($readyBases as $base): ?>
                    <?php $base = new \entities\Base($base['Address'], $base['Name'], $db) ?>
                    <?php if($base->isReady()){?>
                        <div>Готовы: <?= BaseHelper::activeBase($globalTeam[$base->getBaseColorId($round['MissionId'])]) ?></div>
                    <?php }?>
                <?php endforeach ?>
            </div>
        <?php endif ?>
        <div>
            Сценарий: <?= $mission->getName() ?>
        </div>


        <?php if($round['StartDate'] || $readyCount==2): ?>
            <div class="js-reload__ajax-reloader"></div>
        <?php endif ?>
    </div>


    <div class="g-clearfix">
        <form class="b-startround__form" action="/">
            <input type="hidden" name="command" value="forceRoundStart">
            <input type="hidden" name="missionId" value="<?= $mission->id ?>">
            <input type="hidden" name="anyId" value="<?= $request->data['anyId'] ?>">
            <input type="hidden" name="id1" value="<?= $request->data['id1'] ?>">
            <input type="hidden" name="id2" value="<?= $request->data['id2'] ?>">
            <input type="hidden" name="id3" value="<?= $request->data['id3'] ?>">

            <button class="b-btn b-btn_grey js-reloader_off">Начать раунд</button>
        </form>&nbsp;
        <form class="b-cancelround__form" action="/">
            <input type="hidden" name="command" value="cancelMission">
            <button class="b-btn b-btn_grey js-reloader_off">Выбрать другой сценарий</button>
        </form>
    </div>

    <div>
        <div class="g-text-center g-bold g-m20">Настройки раунда</div>
        <div>Режим точек: Перенос флага</div>
        <div>Время раунда: <?= date("i:s", $mission->getMaxTime()) ?></div>
        <div>Очков для победы: <?= $mission->getMaxScore() ?></div>
    </div>

    <div class="g-m-t20">
        <?php
        $redResource = $game->getResource($missionInfo['RedResourceId']);
        $blueResource = $game->getResource($missionInfo['BlueResourceId']);
        ?>

        <div class="g-clearfix grid__row">
            <?php if($missionInfo['RedAllow']==1): ?>
                <div class="grid__col-1-2">
                    <div class="g-m10">
                        <div class="g-text-center">Красные</div>
                        <div>Точек для переноса флага: <?= $missionInfo['RedNPoint'] ?></div>
                        <div>Очков при переносе флага: <?= $missionInfo['RedScoreFlag'] ?></div>
                        <div>Ресурс при переносе: <?= $redResource['Name'] ?></div>
                        <div>Время на захват точки: <?= $missionInfo['RedTimeTake'] ?> c</div>
                    </div>
                </div>
            <?php endif ?>


            <?php if($missionInfo['BlueAllow']==1): ?>
                <div class="grid__col-1-2">
                    <div class="g-m10">
                        <div class="g-text-center">Синие</div>
                        <div>Точек для переноса флага: <?= $missionInfo['BlueNPoint'] ?></div>
                        <div>Очков при переносе флага: <?= $missionInfo['BlueScoreFlag'] ?></div>
                        <div>Ресурс при переносе: <?= $blueResource['Name'] ?></div>
                        <div>Время на захват точки: <?= $missionInfo['BlueTimeTake'] ?> c</div>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

<div class="b-table__wrap">
    <div class="g-text-center g-bold g-m20">Состояние точек</div>

    <table>
        <thead>
        <tr>
            <th>№</th>
            <th>Название точки</th>
            <th>Режим</th>
            <th>Статус</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach($points as $point): ?>
            <?php $pointSetup = $mission->getPointSetup($point['id']) ?>
            <tr>
                <td><?= $point['id'] ?></td>
                <td><?= $point['Name'] ?></td>
                <?php if($missionInfo['RedBase'] == $point['id']): ?>

                    <?php $pointWorkType = \entities\Point::TYPE_CAPTURE_FLAG_RED_BASE; ?>
                    <td><?= ($missionInfo['RedAllow']==1) ? \classes\PointHelper::typeCaptureFlag($pointWorkType) : false ?></td>
                    <td><?= \classes\PointHelper::statusName($point['status'], $mission->pointsType(), \entities\Point::TYPE_2) ?></td>

                <?php elseif($missionInfo['RedAim'] == $point['id']): ?>

                    <?php $pointWorkType = \entities\Point::TYPE_CAPTURE_FLAG_RED_AIM; ?>
                    <td><?= ($missionInfo['RedAllow']==1) ? \classes\PointHelper::typeCaptureFlag($pointWorkType) : false ?></td>
                    <td><?= \classes\PointHelper::statusName($point['status'], $mission->pointsType(), \entities\Point::TYPE_3) ?></td>

                <?php elseif($missionInfo['BlueBase'] == $point['id']): ?>

                    <?php $pointWorkType = \entities\Point::TYPE_CAPTURE_FLAG_BLUE_BASE; ?>
                    <td><?= ($missionInfo['BlueAllow']==1) ? \classes\PointHelper::typeCaptureFlag($pointWorkType) : false ?></td>
                    <td><?= \classes\PointHelper::statusName($point['status'], $mission->pointsType(), \entities\Point::TYPE_2) ?></td>

                <?php elseif($missionInfo['BlueAim'] == $point['id']): ?>

                    <?php $pointWorkType = \entities\Point::TYPE_CAPTURE_FLAG_BLUE_AIM; ?>
                    <td><?= ($missionInfo['BlueAllow']==1) ? \classes\PointHelper::typeCaptureFlag($pointWorkType) : false ?></td>
                    <td><?= \classes\PointHelper::statusName($point['status'], $mission->pointsType(), \entities\Point::TYPE_3) ?></td>

                <?php else: ?>
                    <?php $pointWorkType = \entities\Point::TYPE_CAPTURE_FLAG_POINT; ?>
                    <td><?= ($pointSetup) ? \classes\PointHelper::typeCaptureFlag($pointWorkType) : false ?></td>
                    <td><?= \classes\PointHelper::statusName($point['status'], $mission->pointsType(), \entities\Point::TYPE_1) ?></td>
                <?php endif ?>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>

<div>
    <div class="g-text-center g-bold g-m20">Режим работы баз</div>
    <div class="g-clearfix grid__row js-reload__bases">
        <div class="grid__col-1-2">
            <div>
                <span class="g-text-center g-bold">База красных (<?= $bases['red']->name ?>)</span>
                <?= BaseHelper::statusLabel($bases['red']->getStatus()) ?>
            </div>
            <div>Режим работы: <?= BaseHelper::typeWork($bases['red']->getType()) ?></div>
            <?= BaseHelper::getAdditional($game, $mission, $bases['red'], $bases['red']->getType()) ?>
            <div><?= BaseHelper::calculateToTimeOver(strtotime($round['StartDate']), $missionInfo['TimeWorkBaseRed']) ?></div>
        </div>
        <div class="grid__col-1-2">
            <div>
                <span class="g-text-center g-bold">База синих (<?= $bases['blue']->name ?>)</span>
                <?= BaseHelper::statusLabel($bases['blue']->getStatus()) ?>
            </div>
            <div>Режим работы: <?= BaseHelper::typeWork($bases['blue']->getType()) ?></div>
            <?= BaseHelper::getAdditional($game, $mission, $bases['blue'], $bases['blue']->getType()) ?>
            <div><?= BaseHelper::calculateToTimeOver(strtotime($round['StartDate']), $missionInfo['TimeWorkBaseBlue']) ?></div>
        </div>
    </div>
</div>