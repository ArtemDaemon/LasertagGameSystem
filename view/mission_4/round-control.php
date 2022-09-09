<?php
use classes\BaseHelper;

// Init Players With Flag
$playerWidthFlagRed = $game->getPlayerWithFlagRed($missionInfo['RedTimeTake']);
$playerWidthFlagBlue = $game->getPlayerWithFlagBlue($missionInfo['BlueTimeTake']);
?>

<div class="b-h1">Игра <?= $game->name() ?></div>

<div class="b-game__box">
    <div class="b-h2">Идет игра</div>

    <div class="g-clearfix grid__row">
        <div class="grid__col-1-2">
            <div>
                <div><span class="g-bold">Сценарий:</span> <?= $mission->getName() ?></div>
                <div class="js-reload__round-time">Время раунда: <?= date("i:s", $game->getRoundTime($mission->getMaxTime())) ?></div>
            </div>
        </div>
        <div class="grid__col-1-2">
            <div>
                <div><span class="g-bold">Набранные очки</div>
                <div>
                    <table class="js-reload__score-table b-table__scores">
                        <tr>
                            <td>Красные: <?= $game->getRedScore() ?></td>
                            <td>Синие: <?= $game->getBlueScore() ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <?php if(!$round): ?>
            <div class="js-reload__ajax-reloader"></div>
        <?php endif ?>
    </div>

    <div class="g-clearfix grid__row">
        <div class="grid__col-1-4">
            <form action="/">
                <input type="hidden" name="command" value="roundEnd">
                <input type="hidden" name="forceRoundEnd" value="true">
                <button class="b-btn b-btn_grey js-reloader_off">Прервать раунд</button>
            </form>
        </div>
        <div class="grid__col-1-4">
            <form action="/">
                <input type="hidden" name="command" value="roundEnd">
                <input type="hidden" name="whoWin" value="<?= RED_TEAM ?>">
                <button class="b-btn b-btn_grey js-reloader_off">Победа красных</button>
            </form>
        </div>
        <div class="grid__col-1-4">
            <form action="/">
                <input type="hidden" name="command" value="roundEnd">
                <input type="hidden" name="whoWin" value="<?= DRAW_GAME ?>">
                <button class="b-btn b-btn_grey js-reloader_off">Ничья</button>
            </form>
        </div>
        <div class="grid__col-1-4">
            <form action="/">
                <input type="hidden" name="command" value="roundEnd">
                <input type="hidden" name="whoWin" value="<?= BLUE_TEAM ?>">
                <button class="b-btn b-btn_grey js-reloader_off">Победа синих</button>
            </form>
        </div>
    </div>
</div>

<div class="b-table__wrap">
    <div class="g-text-center g-bold g-m20">Состояние точек</div>

    <div class="g-m20-0">
        <div class="g-clearfix grid__row">
            <?php if($missionInfo['RedAllow']==1 && $playerWidthFlagRed): ?>
                <div class="grid__col-1-2">
                    <div class="g-m10">
                        <div class="g-text-center g-bold">Красные</div>
                        <div>Флаг несет: <?= $playerWidthFlagRed['PlayerData']['PlayerName'] ?> (id <?= $playerWidthFlagRed['TagerId'] ?>)</div>
                        <div>Точек захвачено: <?= $playerWidthFlagRed['pointsTake'] ?>/<?= $missionInfo['RedNPoint'] ?></div>
                        <div>Время до сбороса флага: <?= $playerWidthFlagRed['flagLostTime'] ?> с</div>
                        <div>Перенесено флагов: <?= $playerWidthFlagRed['flagsCaptured'] ?></div>
                    </div>
                </div>
            <?php endif ?>

            <?php if($missionInfo['BlueAllow']==1 && $playerWidthFlagBlue): ?>
                <div class="grid__col-1-2">
                    <div class="g-m10">
                        <div class="g-text-center g-bold">Синие</div>
                        <div>Флаг несет: <?= $playerWidthFlagBlue['PlayerData']['PlayerName'] ?> (id <?= $playerWidthFlagBlue['TagerId'] ?>)</div>
                        <div>Точек захвачено: <?= $playerWidthFlagBlue['pointsTake'] ?>/<?= $missionInfo['BlueNPoint'] ?></div>
                        <div>Время до сбороса флага: <?= $playerWidthFlagBlue['flagLostTime'] ?> с</div>
                        <div>Перенесено флагов: <?= $playerWidthFlagBlue['flagsCaptured'] ?></div>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>

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