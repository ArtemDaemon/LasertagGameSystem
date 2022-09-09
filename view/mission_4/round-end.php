<?php
use classes\RoundHelper;
use classes\BaseHelper;
// Init Players With Flag
$playerWidthFlagRed = $game->getPlayerWithFlagRed($missionInfo['RedTimeTake']);
$playerWidthFlagBlue = $game->getPlayerWithFlagBlue($missionInfo['BlueTimeTake']);
?>
<div class="b-h1">Игра <?= $game->name() ?></div>

<div class="b-game__box">
    <div class="b-h2">
        <?php if($round->whoWin==null): ?>
            Раунд прерван
        <?php elseif($round->whoWin==0): ?>
            Раунд завершен, ничья

        <?php else:?>
            Раунд завершен, победа <?= RoundHelper::drawTeam($round->whoWin); ?>
        <?php endif ?>
    </div>

    <div class="g-clearfix grid__row">
        <div class="grid__col-1-2">
            <div>
                <div><span class="g-bold">Сценарий:</span> <?= $mission->getName() ?></div>
                <div>Время раунда: <?= $round->getGameTime() ?></div>
            </div>
        </div>
        <div class="grid__col-1-2">
            <div>
                <div><span class="g-bold">Набранные очки</div>
                <div class="b-table__scores">
                    <table>
                        <tr>
                            <td>Красные: <?= $round->getRedScore() ?></td>
                            <td>Синие: <?= $round->getBlueScore() ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="g-clearfix">
        <form class="b-startround__form" action="/">
            <input type="hidden" name="command" value="roundEnd">
            <button class="b-btn b-btn_grey js-reloader_off">Статистика</button>
        </form>&nbsp;
        <a href="/" class="b-btn b-btn_grey js-reloader_off">Начать новый раунд</a>
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
                <div><?= BaseHelper::calculateToTimeOver(strtotime($round->getStartDate()), $missionInfo['TimeWorkBaseRed']) ?></div>
            </div>
            <div class="grid__col-1-2">
                <div>
                    <span class="g-text-center g-bold">База синих (<?= $bases['blue']->name ?>)</span>
                    <?= BaseHelper::statusLabel($bases['blue']->getStatus()) ?>
                </div>
                <div>Режим работы: <?= BaseHelper::typeWork($bases['blue']->getType()) ?></div>
                <?= BaseHelper::getAdditional($game, $mission, $bases['blue'], $bases['blue']->getType()) ?>
                <div><?= BaseHelper::calculateToTimeOver(strtotime($round->getStartDate()), $missionInfo['TimeWorkBaseBlue']) ?></div>
            </div>
        </div>
    </div>

</div>