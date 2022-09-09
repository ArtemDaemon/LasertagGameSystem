<?php
use classes\RoundHelper;
use classes\BaseHelper;
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

        <table>
            <thead>
            <tr>
                <th>№</th>
                <th>Название точки</th>
                <th>Время накопления</th>
                <th>Накоплено времени</th>
                <th>Статус</th>
                <th>Захватов Красными</th>
                <th>Захватов Синими</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach($points as $point): ?>
                <?php
                if(!$pointSetup = $mission->getPointSetup($point['id'])) {
                    $point['status'] = \entities\Point::STATUS_NOT_PLAY;
                }
                ?>
                <tr>
                    <td><?= $point['id'] ?></td>
                    <td><?= $point['Name'] ?></td>
                    <td><?= $mission->getNeedTime($point['id'])?></td>
                    <td><?= $point['time'] ?></td>
                    <td><?= \classes\PointHelper::statusName($point['status'], $mission->pointsType(), \entities\Point::TYPE_1) ?></td>
                    <td><?= $point['NRedTake'] ?></td>
                    <td><?= $point['NBlueTake'] ?></td>
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